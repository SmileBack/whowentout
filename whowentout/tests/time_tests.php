<?php

class Time_Tests extends TestGroup
{

    /**
     * @var XCollege
     */
    private $college;

    protected function setup()
    {
        parent::setup();

        $this->college = XCollege::create(array(
                                               'name' => 'GWU',
                                               'facebook_network_id' => '16777270',
                                               'facebook_school_id' => '108727889151725',
                                          ));
        $ci =& get_instance();
        $ci->config->set_item('selected_college_id', $this->college->id);
    }

    protected function teardown()
    {
        parent::teardown();
    }

    public function test_tomorrow()
    {
        $this->college->set_fake_local_time('2011-10-12 11:15:00');
        $this->assert_equal('2011-10-12', $this->college->current_time(TRUE)->format('Y-m-d'));
        $this->assert_equal('2011-10-13', $this->college->tomorrow(TRUE)->format('Y-m-d'));
    }

    public function test_party_day()
    {
        $this->college->set_fake_local_time('2011-10-08 11:00:00');
        $this->assert_equal('2011-10-13', $this->college->party_day(1, TRUE)->format('Y-m-d'));

        $this->college->set_fake_local_time('2011-10-09 11:15:00');
        $this->assert_equal('2011-10-13', $this->college->party_day(1, TRUE)->format('Y-m-d'));

        $this->college->set_fake_local_time('2011-10-13 00:00:00');
        $this->assert_equal('2011-10-14', $this->college->party_day(1, TRUE)->format('Y-m-d'));
    }

}
