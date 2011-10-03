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
        $this->assert_true(!$this->user->is_online());
        $this->user->ping_online();
        $this->assert_true($this->user->is_online(), 'online right after pings');
        $this->assert_true($this->user->is_active(), 'active right after ping');

        $this->college->modify_local_time('+20 minutes');
        $this->assert_true(!$this->user->is_online(), 'offline after no ping');
    }

    function test_idle()
    {
        $this->user->ping_online();
        $this->assert_true(!$this->user->is_idle());

        $this->user->ping_online(FALSE);
        //isn't idle because user just became inactive
        $this->assert_true(!$this->user->is_idle());

        $this->college->modify_local_time('+10 minutes');
        $this->user->ping_online(FALSE);
        $this->assert_true($this->user->is_online());
        $this->assert_true($this->user->is_idle());
    }

}