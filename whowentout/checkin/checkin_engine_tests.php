<?php

class Checkin_Engine_Tests extends TestGroup
{

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

    private $mcfaddens_event;
    private $shadowroom_event;

    function setup()
    {
        $factory = factory('checkin_engine_tests');

        $this->db = $factory->build('test_database');
        $this->checkin_engine = $factory->build('checkin_engine');
        $this->installer = $factory->build('package_installer');

        $this->create_database_schema();
        $this->create_users();

    }

    function clear_database()
    {
        $this->db->execute('SET foreign_key_checks = 0');
        foreach ($this->db->list_table_names() as $table_name) {
            $this->db->destroy_table($table_name);
        }
        $this->db->execute('SET foreign_key_checks = 1');
    }

    function create_database_schema()
    {
        $this->installer->install('whowentout');
    }

    function create_users()
    {
        $this->db->table('users')->create_row(array(
                                                   'first_name' => 'Venkat',
                                                   'last_name' => 'Dinavahi',
                                                   'email' => 'vendiddy@gmail.com',
                                                   'gender' => 'M',
                                              ));

        $this->db->table('users')->create_row(array(
                                                   'first_name' => 'Venkat',
                                                   'last_name' => 'Dinavahi',
                                                   'email' => 'vendiddy@gmail.com',
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

        $this->mcfaddens_event = $this->db->table('events')->create_row(array(
                                                   'name' => 'McFaddens Event',
                                                   'place_id' => $this->mcfaddens_place->id,
                                               ));
        $this->shadowroom_event = $this->db->table('events')->create_row(array(
                                                                             'name' => 'Shadowroom Event',
                                                                             'place_id' => $this->shadowroom_place->id,
                                                                         ));
    }

    function test_basic_checkin()
    {
        
    }

}
