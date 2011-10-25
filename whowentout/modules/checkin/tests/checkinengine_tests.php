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

        $this->clear_database();
        $this->ci =& get_instance();
        $this->seed_data();

        $this->tz = new DateTimeZone('America/New_York');
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
                                           'gender' => 'M',
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
                                           'date' => '2011-10-24',
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
        $this->assert_true(!$checkinEngine->user_has_checked_into_party($this->venkat, $this->party), "venkat hasn't checked in yet");

        $checkinEngine->checkin_user_to_party($this->venkat, $this->party);
        $this->assert_true($checkinEngine->user_has_checked_into_party($this->dan, $this->party), "dan is still checked in");
        $this->assert_true($checkinEngine->user_has_checked_into_party($this->venkat, $this->party), "venkat is now also checked in");

        $this->assert_true(!$checkinEngine->user_has_checked_into_party($this->ted, $this->party), "ted hasn't checked in yet");
    }

    function test_get_users_who_checked_in()
    {
        $this->clear_database();
        $this->seed_data();

        $checkinEngine = new CheckinEngine();

        $this->assert_equal($checkinEngine->get_checkins_for_party($this->party), array(), 'no checkins');

        $checkinEngine->checkin_user_to_party($this->dan, $this->party);
        $checkinEngine->checkin_user_to_party($this->venkat, $this->party);

        $checkins = $checkinEngine->get_checkins_for_party($this->party);

        $this->assert_equal(count($checkins), 2, 'two checkins');
        $this->assert_true(in_array($this->dan, $checkins), 'dan is a checkin');
        $this->assert_true(in_array($this->venkat, $checkins), 'venkat is a checkin');
    }

    function test_user_has_checked_in_on_date()
    {
        $this->clear_database();
        $this->seed_data();

        $checkinEngine = new CheckinEngine();

        $party_date = new XDateTime('2011-10-24 00:00:00', $this->tz);
        $day_after_party = new XDateTime('2011-10-25 00:00:00', $this->tz);

        $this->assert_true( ! $checkinEngine->user_has_checked_in_on_date($this->dan, $party_date), 'dan hasnt checked in on 10-24-2011 yet');
        $this->assert_true( ! $checkinEngine->user_has_checked_in_on_date($this->venkat, $party_date), 'venkat hasnt checked in on 10-24-2011 yet');

        $checkinEngine->checkin_user_to_party($this->dan, $this->party);
        $this->assert_true( $checkinEngine->user_has_checked_in_on_date($this->dan, $party_date), 'dan just checked in on 10-24-2011');
        $this->assert_true( ! $checkinEngine->user_has_checked_in_on_date($this->venkat, $party_date), 'venkat still hasnt checked in on 10-24-2011');

        $this->assert_true( ! $checkinEngine->user_has_checked_in_on_date($this->dan, $day_after_party), 'dan didnt check in on 10-25-2011');
    }
    
}
