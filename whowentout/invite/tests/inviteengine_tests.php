<?php

class InviteEngine_Tests extends PHPUnit_Framework_TestCase
{

    /* @var $database Database */
    private $db;

    /* @var $invite_engine InviteEngine */
    private $invite_engine;

    function setUp()
    {
        $this->db = factory()->build('test_database');
        $this->db->destroy_all_tables();

        /* @var $installer PackageInstaller */
        $installer = factory()->build('test_package_installer');

        $installer->install('WhoWentOutPackage');
        $installer->install('InvitePackage');

        /* @var $invite_engine InviteEngine */
        $this->invite_engine = factory()->build('test_invite_engine');

        $this->create_users();
        $this->create_events();
    }

    function create_users()
    {
        $this->venkat = $this->db->table('users')->create_row(array(
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

    function test_send_basic_invite()
    {
        $this->assertFalse($this->invite_engine->was_invited($this->mcfaddens_event, $this->dan), 'user is not invited by default');

        $this->invite_engine->send_invite($this->mcfaddens_event, $this->venkat, $this->dan);

        $this->assertTrue($this->invite_engine->was_invited($this->mcfaddens_event, $this->dan), 'user invited after invitation sent');
        $this->assertFalse($this->invite_engine->was_invited($this->shadowroom_event, $this->dan), 'user not invited other events');
    }

    function test_invited_twice()
    {
        // send an invite from two different users for an event

        // check that the user has been invited
    }

    function test_accept_invite()
    {
        // send an invite

        // check that the user has been invited

        // accept the invite

        // check that the user has accepted the invite

        // check that the user has checked into the event
    }

}
