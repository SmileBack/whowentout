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

    function test_get_invite_sender()
    {
        $this->assertNull($this->invite_engine->get_invite_sender($this->mcfaddens_event, $this->dan), 'no invite was sent to dan');

        $this->invite_engine->send_invite($this->mcfaddens_event, $this->venkat, $this->dan);
        $this->assertEquals($this->venkat, $this->invite_engine->get_invite_sender($this->mcfaddens_event, $this->dan), 'venkat invited dan to shadowroom');

        $this->assertNull($this->invite_engine->get_invite_sender($this->shadowroom_event, $this->dan), 'no one invited dan to shadowroom');

        $this->invite_engine->send_invite($this->shadowroom_event, $this->kate, $this->dan);
        $this->assertEquals($this->kate, $this->invite_engine->get_invite_sender($this->shadowroom_event, $this->dan), 'kate invited dan to shadowroom');
        $this->assertEquals($this->venkat, $this->invite_engine->get_invite_sender($this->mcfaddens_event, $this->dan), 'dan is invited to shadowroom by kate');

        $this->invite_engine->destroy_invite($this->mcfaddens_event, $this->venkat, $this->dan);
        $this->assertNull($this->invite_engine->get_invite_sender($this->mcfaddens_event, $this->dan), 'dan is no longer invited to mcfaddens');
        $this->assertEquals($this->kate, $this->invite_engine->get_invite_sender($this->shadowroom_event, $this->dan), 'dan is still invited to shadowroom by kate');

        $this->invite_engine->destroy_invite($this->shadowroom_event, $this->kate, $this->dan);
        $this->assertNull($this->invite_engine->get_invite_sender($this->shadowroom_event, $this->dan), 'dan is no longer invited to shadowroom');
    }

    function test_send_duplicate_invite()
    {
        $this->invite_engine->send_invite($this->eden_event, $this->venkat, $this->dan);
        $this->invite_engine->send_invite($this->eden_event, $this->venkat, $this->dan);

        $this->invite_engine->send_invite($this->eden_event, $this->kate, $this->dan);

        $this->assertEquals($this->venkat, $this->invite_engine->get_invite_sender($this->eden_event, $this->dan), 'venkats invite prevails');

        $this->invite_engine->destroy_invite($this->eden_event, $this->venkat, $this->dan);

        $this->assertFalse($this->invite_engine->is_invited($this->eden_event, $this->dan));
    }

    function test_invite_is_sent_condition()
    {
        $this->invite_engine->send_invite($this->eden_event, $this->kate, $this->dan);
        $this->assertTrue($this->invite_engine->invite_is_sent($this->eden_event, $this->kate, $this->dan), 'kate invited dan');

        $this->invite_engine->destroy_invite($this->eden_event, $this->kate, $this->dan);
        $this->assertFalse($this->invite_engine->invite_is_sent($this->eden_event, $this->kate, $this->dan), 'kate no longer invited dan');
    }

}
