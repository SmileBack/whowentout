<?php

class CheckinEngine_Tests extends TestGroup
{

    /**
     * @var XUser
     */
    private $dan;

    /**
     * @var XUser
     */
    private $venkat;

    /**
     * @var XUser
     */
    private $ted;

    protected function setup()
    {
        parent::setup();

        require_once APPPATH . 'classes/checkinengine.class.php';

        $this->clear_database();
        $this->ci =& get_instance();
        $this->seed_data();
    }

    function seed_data()
    {
        $this->college = XCollege::create(array(
                                               'name' => 'GWU',
                                               'facebook_network_id' => '16777270',
                                               'facebook_school_id' => '108727889151725',
                                          ));

        $this->dan = XUser::create(array(
                                        'facebook_id' => '704222664',
                                        'first_name' => 'Dan',
                                        'last_name' => 'Berenholtz',
                                        'gender' => 'M',
                                        'college_id' => $this->college->id,
                                   ));

        $this->venkat = XUser::create(array(
                                           'facebook_id' => '5312146',
                                           'first_name' => 'Venkat',
                                           'last_name' => 'Dinavahi',
                                           'gender' => 'F',
                                           'college_id' => $this->college->id,
                                      ));

        $this->ted = XUser::create(array(
                                        'facebook_id' => '34534543',
                                        'first_name' => 'Ted',
                                        'last_name' => 'Smith',
                                        'gender' => 'M',
                                        'college_id' => $this->college->id,
                                   ));

        $this->place = XPlace::create(array(
                                           'name' => 'McFaddens',
                                           'college_id' => $this->college->id,
                                      ));

        $this->party = XParty::create(array(
                                           'place_id' => $this->place->id,
                                           'date' => '10-24-2011',
                                      ));

        $ci =& get_instance();
        $ci->config->set_item('selected_college_id', $this->college->id);
    }

    function test_basic_user_checkin()
    {
        $this->clear_database();
        $this->seed_data();

        $checkinEngine = new CheckinEngine();

        $this->assert_true(!$checkinEngine->user_has_checked_into_party($this->dan, $this->party));

        $checkinEngine->checkin_user_to_party($this->dan, $this->party);
        $this->assert_true($checkinEngine->user_has_checked_into_party($this->dan, $this->party), "dan just checked in");
        $this->assert_true( ! $checkinEngine->user_has_checked_into_party($this->venkat, $this->party), "venkat hasn't checked in yet" );

        $checkinEngine->checkin_user_to_party($this->venkat, $this->party);
        $this->assert_true( $checkinEngine->user_has_checked_into_party($this->dan, $this->party), "dan is still checked in" );
        $this->assert_true( $checkinEngine->user_has_checked_into_party($this->venkat, $this->party), "venkat is now also checked in" );

        $this->assert_true( ! $checkinEngine->user_has_checked_into_party($this->ted, $this->party), "ted hasn't checked in yet" );
    }

    function test_get_users_who_checked_in()
    {
        $this->clear_database();
        $this->seed_data();

        $checkinEngine = new CheckinEngine();

        $this->assert_equal( $checkinEngine->get_checkins_for_party($this->party), array() , 'no checkins');

        $checkinEngine->checkin_user_to_party($this->dan, $this->party);
        $checkinEngine->checkin_user_to_party($this->venkat, $this->party);

        $checkins = $checkinEngine->get_checkins_for_party($this->party);
        
        $this->assert_equal(count($checkins), 2, 'two checkins');
        $this->assert_true(in_array($this->dan, $checkins), 'dan is a checkin');
        $this->assert_true(in_array($this->venkat, $checkins), 'venkat is a checkin');
    }

}