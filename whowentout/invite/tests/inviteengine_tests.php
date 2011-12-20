<?php

class InviteEngine_Tests extends PHPUnit_Framework_TestCase
{
    private $mcfaddens_place;
    private $shadowroom_place;
    private $eden_place;
    private $venkat;
    private $dan;
    private $kate;
    private $mcfaddens_event;
    private $shadowroom_event;
    private $eden_event;

    /* @var $database Database */
    private $db;

    /* @var $invite_engine InviteEngine */
    private $invite_engine;

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

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
        $this->checkin_engine = factory()->build('test_checkin_engine');

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

        $this->kate = $this->db->table('users')->create_row(array(
            'first_name' => 'Kate',
            'last_name' => 'Smith',
            'email' => 'katesmith@gmail.com',
            'gender' => 'F',
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
        $this->assertFalse($this->invite_engine->is_invited($this->mcfaddens_event, $this->dan), 'user is not invited by default');

        $this->invite_engine->send_invite($this->mcfaddens_event, $this->venkat, $this->dan);

        $this->assertTrue($this->invite_engine->is_invited($this->mcfaddens_event, $this->dan), 'user invited after invitation sent');
        $this->assertFalse($this->invite_engine->is_invited($this->shadowroom_event, $this->dan), 'user not invited other events');

        $this->invite_engine->destroy_invite($this->mcfaddens_event, $this->venkat, $this->dan);

        $this->assertFalse($this->invite_engine->is_invited($this->mcfaddens_event, $this->dan));
    }

    function test_invited_twice()
    {
        $this->assertFalse($this->invite_engine->is_invited($this->mcfaddens_event, $this->dan));

        $this->invite_engine->send_invite($this->mcfaddens_event, $this->venkat, $this->dan);
        $this->invite_engine->send_invite($this->mcfaddens_event, $this->kate, $this->dan);

        $this->assertTrue($this->invite_engine->is_invited($this->mcfaddens_event, $this->dan), 'dan is invited to mcfaddens');

        $this->invite_engine->destroy_invite($this->mcfaddens_event, $this->venkat, $this->dan);
        $this->assertTrue($this->invite_engine->is_invited($this->mcfaddens_event, $this->dan), 'dan is still invited');

        $this->invite_engine->destroy_invite($this->mcfaddens_event, $this->kate, $this->dan);
        $this->assertFalse($this->invite_engine->is_invited($this->mcfaddens_event, $this->dan), 'dan is no longer invited');
    }

    function test_accept_invite()
    {
        // send an invite to dan for mcfaddens
        $this->invite_engine->send_invite($this->mcfaddens_event, $this->venkat, $this->dan);

        // check that the dan has been invited to mcfaddens
        $this->assertTrue($this->invite_engine->is_invited($this->mcfaddens_event, $this->dan));

        // make dan accept the mcfaddens invite

        // check that the dan has accepted mcfaddens invite

        // check that dan has been checked into mcfaddens
    }

}
