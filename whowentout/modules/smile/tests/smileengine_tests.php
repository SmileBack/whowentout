<?php

class SmileEngine_Tests extends TestGroup
{

    protected function setup()
    {
        parent::setup();
        
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

        $this->samantha = XUser::create(array(
                                             'facebook_id' => '5312146',
                                             'first_name' => 'Samantha',
                                             'last_name' => 'Smith',
                                             'gender' => 'F',
                                             'college_id' => $this->college->id,
                                        ));

        $this->jess = XUser::create(array(
                                         'facebook_id' => '456345343',
                                         'first_name' => 'Jess',
                                         'last_name' => 'James',
                                         'gender' => 'F',
                                         'college_id' => $this->college->id,
                                    ));

        $this->michelle = XUser::create(array(
                                             'facebook_id' => '234234234',
                                             'first_name' => 'Michelle',
                                             'last_name' => 'Mack',
                                             'gender' => 'F',
                                             'college_id' => $this->college->id,
                                        ));

        $this->place = XPlace::create(array(
                                           'name' => 'McFaddens',
                                           'college_id' => $this->college->id,
                                      ));

        $this->first_party = XParty::create(array(
                                                 'place_id' => $this->place->id,
                                                 'date' => '11-17-2011',
                                            ));

        $this->second_party = XParty::create(array(
                                                  'place_id' => $this->place->id,
                                                  'date' => '11-18-2011',
                                             ));

        $ci =& get_instance();
        $ci->config->set_item('selected_college_id', $this->college->id);
    }

    function setup_two_parties_and_checkins_scenario()
    {
        $this->clear_database();
        $this->seed_data();

        $checkinEngine = new CheckinEngine();

        $checkinEngine->checkin_user_to_party($this->dan, $this->first_party);
        $checkinEngine->checkin_user_to_party($this->samantha, $this->first_party);

        $checkinEngine->checkin_user_to_party($this->dan, $this->second_party);
        $checkinEngine->checkin_user_to_party($this->samantha, $this->second_party);
        $checkinEngine->checkin_user_to_party($this->jess, $this->second_party);
        $checkinEngine->checkin_user_to_party($this->michelle, $this->second_party);
    }

    function test_smiles_go_through()
    {
        $this->setup_two_parties_and_checkins_scenario();

        $smileEngine = new SmileEngine();
        $smileEngine->send_smile($this->dan, $this->samantha, $this->first_party);

        $who_dan_smiled_at = $smileEngine->get_who_user_smiled_at($this->dan, $this->second_party);
        $this->assert_equal($who_dan_smiled_at, array(), 'dan didnt smile at anyone');

        $smileEngine->send_smile($this->dan, $this->jess, $this->second_party);
        $smileEngine->send_smile($this->dan, $this->michelle, $this->second_party);

        $who_dan_smiled_at = $smileEngine->get_who_user_smiled_at($this->dan, $this->second_party);

        $this->assert_true($smileEngine->smile_was_sent($this->dan, $this->jess, $this->second_party), 'dan smiled at jess');
        $this->assert_true(in_array($this->jess, $who_dan_smiled_at), 'dan smiled at jess');

        $this->assert_true(in_array($this->michelle, $who_dan_smiled_at), 'dan smiled at michelle');

        $this->assert_true( ! $smileEngine->smile_was_sent($this->dan, $this->samantha, $this->second_party), 'dan DID NOT smile at sam');
        $this->assert_true( ! in_array($this->samantha, $who_dan_smiled_at), 'dan DID NOT smile at sam');
    }

    function test_number_of_smiles_received()
    {
        $this->setup_two_parties_and_checkins_scenario();

        $smileEngine = new SmileEngine();

        $num_smiles_dan_received = $smileEngine->get_num_smiles_received($this->dan, $this->second_party);
        $this->assert_equal($num_smiles_dan_received, 0, 'dan got no smiles');

        $smileEngine->send_smile($this->samantha, $this->dan, $this->first_party);
        $num_smiles_dan_received = $smileEngine->get_num_smiles_received($this->dan, $this->second_party);
        $this->assert_equal($num_smiles_dan_received, 0, 'dan got no smiles');

        $smileEngine->send_smile($this->jess, $this->dan, $this->second_party);
        $smileEngine->send_smile($this->michelle, $this->dan, $this->second_party);
        $num_smiles_dan_received = $smileEngine->get_num_smiles_received($this->dan, $this->second_party);
        $this->assert_equal($num_smiles_dan_received, 2, 'dan got two smiles after jess and michelle smiled at him');
    }

    function test_number_of_smiles_remaining()
    {
        $this->setup_two_parties_and_checkins_scenario();

        $smile_engine = new SmileEngine();

        $num_smiles_left_to_give = $smile_engine->get_num_smiles_left_to_give($this->dan, $this->second_party);
        $this->assert_equal($num_smiles_left_to_give, 3, 'dan starts out with 3 smiles');

        $smile_engine->send_smile($this->dan, $this->samantha, $this->first_party);
        $num_smiles_left_to_give = $smile_engine->get_num_smiles_left_to_give($this->dan, $this->second_party);
        $this->assert_equal($num_smiles_left_to_give, 3, 'dan still has 3 smiles left to give at the second party (after using one up at a previous party)');
        
        $smile_engine->send_smile($this->dan, $this->jess, $this->second_party);
        $smile_engine->send_smile($this->dan, $this->michelle, $this->second_party);
        $num_smiles_left_to_give = $smile_engine->get_num_smiles_left_to_give($this->dan, $this->second_party);
        $this->assert_equal($num_smiles_left_to_give, 1, 'dan uses up 2 smiles at the party so he has 1 left to give');
    }

    function test_smile_matches()
    {
        $this->setup_two_parties_and_checkins_scenario();

        $smile_engine = new SmileEngine();

        $smile_engine->send_smile($this->dan, $this->samantha, $this->first_party);
        $smile_engine->send_smile($this->samantha, $this->dan, $this->first_party);

        $smile_engine->send_smile($this->dan, $this->jess, $this->second_party);
        $smile_engine->send_smile($this->dan, $this->michelle, $this->second_party);

        $matches = $smile_engine->get_smile_matches_for_user($this->dan, $this->second_party);
        $this->assert_equal(count($matches), 0, 'no matches for dan at the second party');

        $smile_engine->send_smile($this->jess, $this->dan, $this->second_party);
        $smile_engine->send_smile($this->michelle, $this->dan, $this->second_party);
        $smile_engine->send_smile($this->samantha, $this->dan, $this->second_party);

        $matches = $smile_engine->get_smile_matches_for_user($this->dan, $this->second_party);
        $match_users = $this->get_smile_match_users($this->dan, $matches);
        $this->assert_true( in_array($this->jess, $match_users), 'jess and dan have a match' );
        $this->assert_true( in_array($this->michelle, $match_users), 'michelle and dan have a match' );
        $this->assert_true( ! in_array($this->samantha, $match_users), 'samantha and dan DO NOT have a match' );
    }

    function get_smile_match_users(XUser $for_user, array $matches)
    {
        $users = array();
        foreach ($matches as $match) {
            $users[] = $match->other_user($for_user);
        }
        return $users;
    }

}
