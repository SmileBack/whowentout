<?php

class Checkin_Permission_Tests extends TestGroup
{

    /**
     * @var TestDoor
     */
    private $door;

    function setup()
    {
        parent::setup();
    }

    function seed_data()
    {
        $this->clear_database();
        
        $this->college = XCollege::create(array(
                                               'name' => 'GWU',
                                               'facebook_network_id' => '16777270',
                                               'facebook_school_id' => '108727889151725',
                                          ));

        $this->door = new TestDoor();

        $this->college->set_door( $this->door );

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
    
    function test_doors_closed_checkin()
    {
        $this->seed_data();
        
        $checkin_permission = new CheckinPermission();

        $this->door->close();
        $this->assert_true( !$checkin_permission->check($this->dan, $this->party), 'dan cant checkin since doors are closed');

        $this->door->open();
        $this->assert_true( $checkin_permission->check($this->dan, $this->party), 'dan can checkin after doors have opened');
    }

    function test_duplicate_checkin()
    {
        $this->seed_data();
        
        $checkin_permission = new CheckinPermission();
        $checkinEngine = new CheckinEngine();

        $this->door->open();
        $checkinEngine->checkin_user_to_party($this->dan, $this->party);
        $this->assert_true( ! $checkin_permission->check($this->dan, $this->party), 'dan cant checkin twice to a party');
    }

    function test_viewing_party_without_checking_in()
    {
        $this->seed_data();
        $this->door->open();

        $checkin_engine = new CheckinEngine();
        $view_party_permission = new ViewPartyPermission();

        $this->assert_true( !$view_party_permission->check($this->dan, $this->party), 'dan cant view a party he hasnt checked into' );
        $checkin_engine->checkin_user_to_party($this->dan, $this->party);
        $this->assert_true( $view_party_permission->check($this->dan, $this->party), 'dan CAN view a party he HAS checked into' );
    }

}

class TestDoor
{

    private $open = FALSE;

    function is_open()
    {
        return $this->open;
    }

    function open()
    {
        $this->open = TRUE;
    }

    function close()
    {
        $this->open = FALSE;
    }

}
