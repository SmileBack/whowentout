<?php

class Online_Tests extends TestGroup
{

    /**
     * @var XCollege
     */
    private $college;

    /**
     * @var XUser
     */
    private $user;

    protected function setup()
    {
        parent::setup();

        $this->clear_database();
        $this->ci =& get_instance();
        $this->ci->load->library('presence');
        $this->presence = $this->ci->presence;

        //disable events for side effects
        $this->ci->event->disable();

        $this->college = XCollege::create(array(
                                               'name' => 'GWU',
                                               'facebook_network_id' => '16777270',
                                               'facebook_school_id' => '108727889151725',
                                          ));

        $this->user = XUser::create(array(
                                         'first_name' => 'Bob',
                                         'last_name' => 'Jones',
                                         'college_id' => $this->college->id,
                                    ));

        $ci =& get_instance();
        $ci->config->set_item('selected_college_id', $this->college->id);
    }

    protected function teardown()
    {
        parent::teardown();
    }

    function test_online()
    {
        $presence = $this->presence;
        $user = $this->user;
        $this->assert_true(!$presence->is_online($user->id), 'user is initially offline');

        $token = $presence->ping_online($user->id);
        $this->assert_true($presence->is_online($user->id), 'user opens one tab is online');

        $presence->ping_offline($user->id, 'a bogus token');
        $this->assert_true($presence->is_online($user->id), 'user pings offline with a bogus token, so he is still online');

        $presence->ping_offline($user->id, $token);
        $this->assert_true(!$presence->is_online($user->id), 'user closes only one tab, and is therefore offline');
    }

    function test_online_multiple_browsers()
    {
        $presence = $this->presence;
        $user = $this->user;
        $this->assert_true(!$presence->is_online($user->id));

        $browser_token_1 = $presence->ping_online($user->id);
        $this->assert_true($presence->is_online($user->id), 'user opened first tab so he is online');

        $browser_token_2 = $presence->ping_online($user->id);
        $this->assert_true($presence->is_online($user->id), 'user opened one more tab so still online');

        $presence->ping_offline($user->id, $browser_token_1);
        $this->assert_true($presence->is_online($user->id), 'still online since user only closed one of his tabs');

        $presence->ping_offline($user->id, $browser_token_2);
        $this->assert_true(!$presence->is_online($user->id), 'user closed both tabs, so user is offline');
    }

}
