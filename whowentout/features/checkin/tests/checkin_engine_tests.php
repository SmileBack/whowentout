<?php

class Checkin_Engine_Tests extends PHPUnit_Framework_TestCase
{

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var Database
     */
    private $db;

    /**
     * @var CheckinEngine
     */
    private $checkin_engine;

    /**
     * @var PackageInstaller
     */
    private $installer;

    private $ven;
    private $dan;

    private $mcfaddens_place;
    private $shadowroom_place;
    private $eden_place;

    private $mcfaddens_event;
    private $shadowroom_event;
    private $eden_event;

    function setUp()
    {
        $this->db = build('database');
        $this->db->destroy_all_tables();

        $this->create_database_schema();

        $this->create_users();
        $this->create_events();

        $this->checkin_engine = build('checkin_engine');
    }

    function create_database_schema()
    {
        $this->installer = build('package_installer');
        $this->installer->install('WhoWentOutPackage');
        $this->installer->install('CheckinPackage');
    }

    function create_users()
    {
        $this->ven = $this->db->table('users')->create_row(array(
                                                                'first_name' => 'Venkat',
                                                                'last_name' => 'Dinavahi',
                                                                'email' => 'vendiddy@gmail.com',
                                                                'gender' => 'M',
                                                           ));

        $this->dan = $this->db->table('users')->create_row(array(
                                                                'first_name' => 'Dan',
                                                                'last_name' => 'Berenholtz',
                                                                'email' => 'berenholtzdan@gmail.com',
                                                                'gender' => 'M',
                                                           ));
    }

    function create_events()
    {
        $this->mcfaddens_place = $this->db->table('places')->create_row(array(
                                                                             'name' => 'McFaddens',
                                                                        ));
        $this->shadowroom_place = $this->db->table('places')->create_row(array(
                                                                              'name' => 'Shadowroom'
                                                                         ));
        $this->eden_place = $this->db->table('places')->create_row(array(
                                                                        'name' => 'Eden',
                                                                   ));

        $this->mcfaddens_event = $this->db->table('events')->create_row(array(
                                                                             'name' => 'McFaddens Event',
                                                                             'place_id' => $this->mcfaddens_place->id,
                                                                             'date' => new DateTime('2011-12-09', new DateTimeZone('UTC')),
                                                                        ));
        $this->shadowroom_event = $this->db->table('events')->create_row(array(
                                                                              'name' => 'Shadowroom Event',
                                                                              'place_id' => $this->shadowroom_place->id,
                                                                              'date' => new DateTime('2011-12-10', new DateTimeZone('UTC')),
                                                                         ));
        $this->eden_event = $this->db->table('events')->create_row(array(
                                                                        'name' => 'Eden Event',
                                                                        'place_id' => $this->eden_place->id,
                                                                        'date' => new DateTime('2011-12-10', new DateTimeZone('UTC')),
                                                                   ));
    }

    function test_basic_checkin()
    {
        $this->assertTrue(!$this->checkin_engine->user_has_checked_into_event($this->ven, $this->mcfaddens_event));

        $this->checkin_engine->checkin_user_to_event($this->ven, $this->mcfaddens_event);
        $this->assertTrue($this->checkin_engine->user_has_checked_into_event($this->ven, $this->mcfaddens_event));

        $this->checkin_engine->remove_checkin_on_date($this->ven, $this->mcfaddens_event->date);
        $this->assertTrue(!$this->checkin_engine->user_has_checked_into_event($this->ven, $this->mcfaddens_event));
    }
    
    function test_switch_checkin()
    {
        $this->assertEquals(0, $this->checkin_engine->get_checkin_count($this->shadowroom_event));

        $this->checkin_engine->checkin_user_to_event($this->dan, $this->shadowroom_event);
        $this->assertTrue($this->checkin_engine->user_has_checked_into_event($this->dan, $this->shadowroom_event));
        $this->assertEquals(1, $this->checkin_engine->get_checkin_count($this->shadowroom_event));

        $this->checkin_engine->checkin_user_to_event($this->dan, $this->eden_event);

        $this->assertTrue($this->checkin_engine->user_has_checked_into_event($this->dan, $this->eden_event));
        $this->assertTrue(!$this->checkin_engine->user_has_checked_into_event($this->dan, $this->shadowroom_event));

        $this->assertEquals(0, $this->checkin_engine->get_checkin_count($this->shadowroom_event));
        $this->assertEquals(1, $this->checkin_engine->get_checkin_count($this->eden_event));
    }
    
}
