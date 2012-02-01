<?php

class EntourageEngineTests extends PHPUnit_Framework_TestCase
{

    /* @var $entourage_engine EntourageEngine */
    private $entourage_engine;

    /* @var $db Database */
    private $db;

    function setUp()
    {
        /* @var $db Database */
        $this->db = build('database');
        $this->db->destroy_all_tables();

        /* @var $installer PackageInstaller */
        $installer = build('package_installer');

        $installer->install('WhoWentOutPackage');
        $installer->install('EntouragePackage');

        $this->entourage_engine = build('entourage_engine');

        $this->create_users();
    }

    function tearDown()
    {
    }

    private function create_users()
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

        $this->kat = $this->db->table('users')->create_row(array(
            'first_name' => 'Kat',
            'last_name' => 'Smith',
            'email' => 'k.smith@gmail.com',
            'gender' => 'F',
        ));
    }

    function test_basic()
    {
        $this->assertTrue(count($this->entourage_engine->get_pending_requests($this->kat)) == 0, 'kat has no pending requests');
        $this->assertFalse($this->entourage_engine->request_was_sent($this->ven, $this->kat));
        $this->assertFalse($this->entourage_engine->request_was_sent($this->dan, $this->kat));

        $this->entourage_engine->send_request($this->ven, $this->kat);
        $pending_requests = $this->entourage_engine->get_pending_requests($this->kat);
        $this->assertTrue(count($pending_requests) > 0, 'kat does have pending requests');
        $this->assertEquals('Venkat', $pending_requests[0]->sender->first_name, 'venkat sent the request');

        $this->assertTrue($this->entourage_engine->request_was_sent($this->ven, $this->kat));
        $this->assertFalse($this->entourage_engine->request_was_sent($this->dan, $this->kat));

        $request = $this->entourage_engine->get_request_between($this->ven, $this->kat);
        $this->assertEquals($request->status, 'pending');

        $this->assertTrue(count($this->entourage_engine->get_entourage_users($this->kat)) == 0, 'no entourage before accepting');

        $this->entourage_engine->accept_request($request);
        $this->assertEquals($request->status, 'accepted');
        $this->assertTrue($this->entourage_engine->request_was_sent($this->ven, $this->kat), 'request still has been sent after it was accepted');

        $kats_entourage = $this->entourage_engine->get_entourage_users($this->kat);
        $this->assertEquals($kats_entourage[0]->first_name, 'Venkat');

        $venkats_entourage = $this->entourage_engine->get_entourage_users($this->ven);
        $this->assertEquals($venkats_entourage[0]->first_name, 'Kat');
    }

    function test_double_request()
    {
        $this->entourage_engine->send_request($this->ven, $this->kat);
        $this->entourage_engine->send_request($this->ven, $this->kat);
    }



}
