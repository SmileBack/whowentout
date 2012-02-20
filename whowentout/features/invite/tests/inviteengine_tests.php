<?php

class InviteEngine_Tests extends PHPUnit_Framework_TestCase
{
    private $mcfaddens_place;
    private $shadowroom_place;
    private $eden_place;

    private $venkat;
    private $dan;
    private $kate;
    private $doron;

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
        $this->db = build('database');
        $this->db->destroy_all_tables();

        /* @var $installer PackageInstaller */
        $installer = build('package_installer');

        $installer->install('WhoWentOutPackage');
        $installer->install('CheckinPackage');
        $installer->install('InvitePackage');

        $this->checkin_engine = build('checkin_engine');
        $this->invite_engine = build('invite_engine');

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

        $this->doron = $this->db->table('users')->create_row(array(
            'first_name' => 'Doron',
            'last_name' => 'Berenholtz',
            'email' => 'doronberenholtz@gmail.com',
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
        $this->assertFalse($this->invite_engine->is_invited($this->mcfaddens_event, $this->dan), 'user is not invited by default');

        $this->invite_engine->send_invite($this->mcfaddens_event, $this->venkat, $this->dan);

        $invite = $this->invite_engine->fetch_invite($this->mcfaddens_event, $this->venkat, $this->dan);

        $this->assertEquals($invite->status, 'pending', 'invitation status is pending');
        $this->assertTrue($this->invite_engine->is_invited($this->mcfaddens_event, $this->dan), 'user invited after invitation sent');
        $this->assertFalse($this->invite_engine->is_invited($this->shadowroom_event, $this->dan), 'user not invited other events');

        $this->invite_engine->destroy_invite($this->mcfaddens_event, $this->venkat, $this->dan);

        $this->assertFalse($this->invite_engine->is_invited($this->mcfaddens_event, $this->dan));
    }

    function test_get_invite_senders()
    {
        $senders = $this->invite_engine->get_invite_senders($this->mcfaddens_event, $this->dan);
        $this->assertEmpty($senders, 'no invite was sent to dan');

        $this->invite_engine->send_invite($this->mcfaddens_event, $this->venkat, $this->dan);
        $senders = $this->invite_engine->get_invite_senders($this->mcfaddens_event, $this->dan);
        $this->assertContains($this->venkat, $senders, 'venakt invited dan to mcfaddens');

        $senders = $this->invite_engine->get_invite_senders($this->shadowroom_event, $this->dan);
        $this->assertEmpty($senders, 'no one invited dan to shadowroom');

        $this->invite_engine->send_invite($this->shadowroom_event, $this->kate, $this->dan);
        $this->assertContains($this->kate, $this->invite_engine->get_invite_senders($this->shadowroom_event, $this->dan), 'kate invited dan to shadowroom');
        $this->assertContains($this->venkat, $this->invite_engine->get_invite_senders($this->mcfaddens_event, $this->dan), 'venkat still invited dan to mcfaddens');

        $this->invite_engine->destroy_invite($this->mcfaddens_event, $this->venkat, $this->dan);
        $this->assertEmpty($this->invite_engine->get_invite_senders($this->mcfaddens_event, $this->dan), 'dan is no longer invited to mcfaddens');
        $this->assertContains($this->kate, $this->invite_engine->get_invite_senders($this->shadowroom_event, $this->dan), 'dan is still invited to shadowroom by kate');

        $this->invite_engine->destroy_invite($this->shadowroom_event, $this->kate, $this->dan);
        $this->assertEmpty($this->invite_engine->get_invite_senders($this->shadowroom_event, $this->dan), 'dan is no longer invited to shadowroom');
    }

    function test_send_multiple_invites()
    {
        $this->invite_engine->send_invite($this->eden_event, $this->venkat, $this->dan);
        $this->invite_engine->send_invite($this->eden_event, $this->venkat, $this->dan);

        $senders = $this->invite_engine->get_invite_senders($this->eden_event, $this->dan);
        $this->assertContains($this->venkat, $senders);
        $this->assertCount(1, $senders);

        $this->invite_engine->send_invite($this->eden_event, $this->kate, $this->dan);
        $senders = $this->invite_engine->get_invite_senders($this->eden_event, $this->dan);
        $this->assertContains($this->kate, $senders);
        $this->assertCount(2, $senders);

        $this->invite_engine->destroy_invite($this->eden_event, $this->venkat, $this->dan);
    }

    function test_invite_is_sent_condition()
    {
        $this->invite_engine->send_invite($this->eden_event, $this->kate, $this->dan);
        $this->assertTrue($this->invite_engine->invite_is_sent($this->eden_event, $this->kate, $this->dan), 'kate invited dan');

        $this->invite_engine->destroy_invite($this->eden_event, $this->kate, $this->dan);
        $this->assertFalse($this->invite_engine->invite_is_sent($this->eden_event, $this->kate, $this->dan), 'kate no longer invited dan');
    }

    function test_invite_is_accepted_after_checkin()
    {
        $this->invite_engine->send_invite($this->mcfaddens_event, $this->venkat, $this->dan);
        $ven_invite = $this->invite_engine->fetch_invite($this->mcfaddens_event, $this->venkat, $this->dan);

        $this->invite_engine->send_invite($this->mcfaddens_event, $this->kate, $this->dan);
        $kate_invite = $this->invite_engine->fetch_invite($this->mcfaddens_event, $this->kate, $this->dan);

        $this->assertEquals('pending', $ven_invite->status, 'invite status is pending');
        $this->assertEquals('pending', $kate_invite->status, 'invite status is pending');

        $this->checkin_engine->checkin_user_to_event($this->dan, $this->mcfaddens_event);
        $this->assertEquals('accepted', $ven_invite->status, 'invite status is accepted after checking in');
        $this->assertEquals('accepted', $kate_invite->status, 'invite status is accepted after checking in');
    }

    function test_invite_is_pending_after_switch_checkin()
    {
        //shadowroom and eden are on the same date
        $this->invite_engine->send_invite($this->shadowroom_event, $this->venkat, $this->dan);
        $ven_invite = $this->invite_engine->fetch_invite($this->shadowroom_event, $this->venkat, $this->dan);

        $this->assertEquals('pending', $ven_invite->status, 'invite status is pending');

        $this->checkin_engine->checkin_user_to_event($this->dan, $this->shadowroom_event);
        $this->assertEquals('accepted', $ven_invite->status, 'invite status is accepted');

        $this->checkin_engine->checkin_user_to_event($this->dan, $this->eden_event);
        $this->assertEquals('pending', $ven_invite->status, 'invite is pending after checkin switch');
    }

    function test_invite_multiple_invite_checkins()
    {
        $this->invite_engine->send_invite($this->shadowroom_event, $this->venkat, $this->dan);
        $ven_invite = $this->invite_engine->fetch_invite($this->shadowroom_event, $this->venkat, $this->dan);

        $this->invite_engine->send_invite($this->shadowroom_event, $this->kate, $this->doron);
        $kate_invite = $this->invite_engine->fetch_invite($this->shadowroom_event, $this->kate, $this->doron);

        $this->assertEquals('pending', $ven_invite->status);
        $this->assertEquals('pending', $kate_invite->status);

        $this->checkin_engine->checkin_user_to_event($this->dan, $this->shadowroom_event);
        $this->assertEquals('accepted', $ven_invite->status);

        $this->checkin_engine->checkin_user_to_event($this->doron, $this->shadowroom_event);
        $this->assertEquals('accepted', $kate_invite->status);
        $this->assertEquals('accepted', $ven_invite->status);
    }

}
