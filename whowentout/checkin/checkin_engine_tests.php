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

    function setup()
    {
        $factory = factory('checkin_engine_tests');

        $this->db = $factory->build('test_database');
        $this->checkin_engine = $factory->build('checkin_engine');
        $this->installer = $factory->build('package_installer');
        $this->create_database_schema();
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

    function test_get_checkins()
    {

    }

    function test_user_has_checked_into_particular_event()
    {
    }

    function test_users_who_checked_into_event()
    {

    }

    function test_user_can_check_into_event()
    {
        
    }

}
