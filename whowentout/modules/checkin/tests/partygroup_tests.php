<?php

class PartyGroup_Tests extends TestGroup
{

    private $mcfaddens;

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

        $this->next_day_party = XParty::create(array(
                                                    'place_id' => $this->mcfaddens->id,
                                                    'date' => '2011-10-25',
                                               ));

        $ci =& get_instance();
        $ci->config->set_item('selected_college_id', $this->college->id);
    }

    function test_get_parties()
    {
        $this->clear_database();
        $this->seed_data();

        $clock = new Clock($this->tz);
        $clock->set_time('2011-10-23 00:00:00');

        $party_group = new PartyGroup($clock, new XDateTime('2011-10-24 00:00:00', $this->tz));
        
        $parties = $party_group->get_parties();
        $this->assert_true(in_array($this->party, $parties));
        $this->assert_true(in_array($this->same_day_party, $parties));
        $this->assert_true(!in_array($this->next_day_party, $parties));

        $next_day_party_group = new PartyGroup($clock, new XDateTime('2011-10-25 00:00:00', $this->tz));
        $next_day_parties = $next_day_party_group->get_parties();
        $this->assert_equal($next_day_parties[0], $this->next_day_party);
    }
    
}
