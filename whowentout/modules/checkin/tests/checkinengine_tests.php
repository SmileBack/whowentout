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

    private $mcfaddens;
    private $teatro;

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

        $this->mcfaddens = XPlace::create(array(
                                           'name' => 'McFaddens',
                                           'college_id' => $this->college->id,
                                      ));

        $this->teatro = XPlace::create(array(
                                           'name' => 'Teatro',
                                           'college_id' => $this->college->id,
                                      ));

        $this->party = XParty::create(array(
                                           'place_id' => $this->mcfaddens->id,
                                           'date' => '2011-10-24',
                                      ));

        $this->same_day_party = XParty::create(array(
                                                   'place_id' => $this->teatro->id,
                                                   'date' => '2011-10-24',
                                               ));
        

        $this->second_party = XParty::create(array(
                                                  'place_id' => $this->mcfaddens->id,
                                                  'date' => '2011-10-25',
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

        $checkin_engine = new CheckinEngine();

        $this->assert_equal($checkin_engine->get_checkins_for_party($this->party), array(), 'no checkins');

        $checkin_engine->checkin_user_to_party($this->dan, $this->party);
        $checkin_engine->checkin_user_to_party($this->venkat, $this->party);

        $checkins = $checkin_engine->get_checkins_for_party($this->party);

        $this->assert_equal(count($checkins), 2, 'two checkins');
        $this->assert_true(in_array($this->dan, $checkins), 'dan is a checkin');
        $this->assert_true(in_array($this->venkat, $checkins), 'venkat is a checkin');
    }

    function test_user_has_checked_in_on_date()
    {
        $this->clear_database();
        $this->seed_data();

        $checkin_engine = new CheckinEngine();

        $party_date = new XDateTime('2011-10-24 00:00:00', $this->tz);
        $day_after_party = new XDateTime('2011-10-25 00:00:00', $this->tz);

        $this->assert_true(!$checkin_engine->user_has_checked_in_on_date($this->dan, $party_date), 'dan hasnt checked in on 10-24-2011 yet');
        $this->assert_true(!$checkin_engine->user_has_checked_in_on_date($this->venkat, $party_date), 'venkat hasnt checked in on 10-24-2011 yet');

        $checkin_engine->checkin_user_to_party($this->dan, $this->party);
        $this->assert_true($checkin_engine->user_has_checked_in_on_date($this->dan, $party_date), 'dan just checked in on 10-24-2011');
        $this->assert_true(!$checkin_engine->user_has_checked_in_on_date($this->venkat, $party_date), 'venkat still hasnt checked in on 10-24-2011');

        $this->assert_true(!$checkin_engine->user_has_checked_in_on_date($this->dan, $day_after_party), 'dan didnt check in on 10-25-2011');
    }

    function test_num_checkins_for_user()
    {
        $this->clear_database();
        $this->seed_data();

        $checkin_engine = new CheckinEngine();

        $this->assert_equal($checkin_engine->get_num_checkins_for_user($this->dan), 0, 'dan has no checkins');

        $checkin_engine->checkin_user_to_party($this->dan, $this->party);
        $this->assert_equal($checkin_engine->get_num_checkins_for_user($this->dan), 1, 'dan has 1 checkin');
        $this->assert_equal($checkin_engine->get_num_checkins_for_user($this->venkat), 0, 'venkat has no checkins');

        $checkin_engine->checkin_user_to_party($this->dan, $this->second_party);
        $this->assert_equal($checkin_engine->get_num_checkins_for_user($this->dan), 2, 'dan has 2 checkins');
    }


    function test_get_checkin_for_date()
    {
        $this->clear_database();
        $this->seed_data();

        $checkin_engine = new CheckinEngine();

        $party_date = new XDateTime('2011-10-24 00:00:00', $this->tz);
        $second_party_date = new XDateTime('2011-10-25 00:00:00', $this->tz);

        $this->assert_equal($checkin_engine->get_checkin_for_date($this->dan, $party_date), NULL, 'dan has no checkins on the 24th');

        $checkin_engine->checkin_user_to_party($this->dan, $this->party);
        $this->assert_equal($checkin_engine->get_checkin_for_date($this->dan, $party_date), $this->party, 'dan has a checkin on the 24th');
        $this->assert_equal($checkin_engine->get_checkin_for_date($this->dan, $second_party_date), NULL, 'dan has no checkins on the 25th');

        $this->assert_equal($checkin_engine->get_checkin_for_date($this->venkat, $party_date), NULL, 'venkat still has no checkins');
    }

    function test_select_party()
    {
        $this->clear_database();
        $this->seed_data();

        $party_date = new XDateTime('2011-10-24 00:00:00', $this->tz);

        $checkin_engine = new CheckinEngine();

        $checkin_engine->checkin_user_to_party($this->dan, $this->party);
        $checked_in_party = $checkin_engine->get_checkin_for_date($this->dan, $party_date);
        $this->assert_equal($checked_in_party, $this->party);

        $checkin_engine->checkin_user_to_party($this->dan, $this->same_day_party);
        $checked_in_party = $checkin_engine->get_checkin_for_date($this->dan, $party_date);
        $this->assert_equal($checked_in_party, $this->same_day_party);
    }

}
