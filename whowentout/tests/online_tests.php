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


    /**
     * @var XUser
     */
    private $second_user;

    /**
     * @var Presence
     */
    private $presence;

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
                                         'facebook_id' => '704222664',
                                         'first_name' => 'Leon',
                                         'last_name' => 'Harari',
                                         'gender' => 'M',
                                         'college_id' => $this->college->id,
                                    ));

        $this->second_user = XUser::create(array(
                                         'facebook_id' => '5312146',
                                         'first_name' => 'Emily',
                                         'last_name' => 'Aden',
                                         'gender' => 'F',
                                         'college_id' => $this->college->id,
                                    ));


        $ci =& get_instance();
        $ci->config->set_item('selected_college_id', $this->college->id);
    }

    protected function teardown()
    {
        parent::teardown();
    }

    function test_mark_online()
    {
        $user = $this->user;
        $presence = $this->presence;

        $this->assert_true(!$presence->is_online($user->id), 'user is initially offline');

        $presence->mark_online($user->id);
        $this->assert_true($presence->is_online($user->id), 'user is online after being marked online');

        $presence->mark_offline($user->id);
        $this->assert_true(!$presence->is_online($user->id), 'user is offline after being online and being marked offline');

        $presence->mark_offline($user->id);
        $this->assert_true(!$presence->is_online($user->id), 'is still offline after being marked offline twice');
    }

    function test_online_user_ids()
    {
        $presence = $this->presence;
        $first_user = $this->user;
        $second_user = $this->second_user;

        $this->assert_equal( count($presence->get_online_user_ids()), 0, 'initially no users are online' );

        $presence->mark_online($first_user->id);
        $this->assert_true( in_array($first_user->id, $presence->get_online_user_ids()), 'first user is in array' );

        $presence->mark_online($second_user->id);
        $this->assert_true( in_array($first_user->id, $presence->get_online_user_ids()), 'first user is online' );
        $this->assert_true( in_array($second_user->id, $presence->get_online_user_ids()), 'second user is online' );

        $presence->mark_offline($first_user->id);
        $this->assert_true( ! in_array($first_user->id, $presence->get_online_user_ids()), 'second user is not in list after going offline');

        $presence->mark_offline($second_user->id);
        $this->assert_equal( count($presence->get_online_user_ids()), 0, 'no users are online after everyone signs off' );
    }

}
