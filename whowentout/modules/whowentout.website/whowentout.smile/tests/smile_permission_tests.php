<?php

class Smile_Permission_Tests extends TestGroup
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
        $this->clear_database();

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
                                           'facebook_id' => '234235435',
                                           'first_name' => 'Venkat',
                                           'last_name' => 'Dinavahi',
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

        $this->nancy = XUser::create(array(
                                          'facebook_id' => '23423443',
                                          'first_name' => 'Nancy',
                                          'last_name' => 'Yu',
                                          'gender' => 'F',
                                          'college_id' => $this->college->id,
                                     ));

        $this->place = XPlace::create(array(
                                           'name' => 'McFaddens',
                                           'college_id' => $this->college->id,
                                      ));

        $this->first_party = XParty::create(array(
                                                 'place_id' => $this->place->id,
                                                 'date' => '2011-10-27',
                                            ));

        $this->second_party = XParty::create(array(
                                                  'place_id' => $this->place->id,
                                                  'date' => '2011-10-28',
                                             ));

        $ci =& get_instance();
        $ci->config->set_item('selected_college_id', $this->college->id);
    }


    function test_smile_limit_per_party()
    {
        $this->seed_data();

        $smile_engine = new SmileEngine();
        $checkin_engine = new CheckinEngine();
        $smile_permission = new SmilePermission();

        $users = array($this->dan, $this->samantha, $this->jess, $this->michelle, $this->nancy);
        foreach ($users as $user) {
            $checkin_engine->checkin_user_to_party($user, $this->first_party);
            $checkin_engine->checkin_user_to_party($user, $this->second_party);
        }

        $smile_engine->send_smile($this->dan, $this->samantha, $this->first_party);
        $smile_engine->send_smile($this->dan, $this->jess, $this->first_party);
        $smile_engine->send_smile($this->dan, $this->michelle, $this->first_party);

        $this->assert_true(!$smile_permission->check($this->dan, $this->nancy, $this->first_party), 'dan cant smile at four girls at one party');
        $this->assert_true($smile_permission->check($this->dan, $this->nancy, $this->second_party), 'dan can smile at a second party because he gets more smiles');
    }

    function test_sending_duplicate_smiles()
    {
        $this->seed_data();

        $smile_engine = new SmileEngine();
        $checkin_engine = new CheckinEngine();
        $smile_permission = new SmilePermission();

        $users = array($this->dan, $this->samantha, $this->jess, $this->michelle, $this->nancy);
        foreach ($users as $user) {
            $checkin_engine->checkin_user_to_party($user, $this->first_party);
            $checkin_engine->checkin_user_to_party($user, $this->second_party);
        }

        $smile_engine->send_smile($this->dan, $this->samantha, $this->first_party);

        $this->assert_true(!$smile_permission->check($this->dan, $this->samantha, $this->first_party), 'dan cant smile at the same person at the same party twice');
        $this->assert_true($smile_permission->check($this->dan, $this->samantha, $this->second_party), 'can can smile at the same person if its at a different party');
    }

    function test_same_gender_smile()
    {
        $this->seed_data();

        $checkin_engine = new CheckinEngine();
        $smile_permission = new SmilePermission();

        $users = array($this->dan, $this->venkat, $this->samantha, $this->jess);
        foreach ($users as $user) {
            $checkin_engine->checkin_user_to_party($user, $this->first_party);
        }

        $this->assert_true(!$smile_permission->check($this->samantha, $this->jess, $this->first_party), 'two girls cant smile at each other');
        $this->assert_true(!$smile_permission->check($this->dan, $this->venkat, $this->first_party), 'two guys cant smile at each other');
    }

    function test_smiles_when_not_in_party()
    {
        $this->seed_data();
        
        $smile_engine = new SmileEngine();
        $checkin_engine = new CheckinEngine();
        $smile_permission = new SmilePermission();

        $checkin_engine->checkin_user_to_party($this->dan, $this->first_party);
        $checkin_engine->checkin_user_to_party($this->jess, $this->second_party);

        $this->assert_true(!$smile_permission->check($this->dan, $this->jess, $this->first_party), 'dan cant smile at someone who didnt attend the party');
        $this->assert_true(!$smile_permission->check($this->dan, $this->jess, $this->second_party), 'dan cant smile in a party he didnt attend');
    }

}
