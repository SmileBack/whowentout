<?php

class Reference_Tests extends TestGroup
{
    /**
     * @var Database
     */
    private $db;

    function setup()
    {
        $this->db = factory()->build('test_database');

        $this->clear_database($this->db);
        $this->create_tables();
        $this->seed_data();
    }

    function create_tables()
    {
        $this->db->create_table('users', array(
                                              'id' => array('type' => 'id'),
                                              'name' => array('type' => 'string'),
                                         ));

        $this->db->create_table('events', array(
                                               'id' => array('type' => 'id'),
                                               'name' => array('type' => 'string'),
                                          ));

        $this->db->create_table('checkins', array(
                                                 'id' => array('type' => 'id'),
                                                 'user_id' => array('type' => 'integer'),
                                                 'event_id' => array('type' => 'integer'),
                                            ));
        $this->db->table('checkins')->create_foreign_key('user_id', 'users', 'id');
        $this->db->table('checkins')->create_foreign_key('event_id', 'events', 'id');

        $this->db->create_table('networks', array(
                                                 'id' => array('type' => 'id'),
                                                 'name' => array('type' => 'string'),
                                            ));

        $this->db->create_table('user_networks', array(
                                                      'id' => array('type' => 'id'),
                                                      'user_id' => array('type' => 'integer'),
                                                      'network_id' => array('type' => 'integer'),
                                                 ));
        $this->db->table('user_networks')->create_foreign_key('user_id', 'users', 'id');
        $this->db->table('user_networks')->create_foreign_key('network_id', 'networks', 'id');
    }

    function seed_data()
    {
        $this->venkat = $this->db->table('users')->create_row(array(
                                                                   'name' => 'venkat',
                                                              ));

        $this->dan = $this->db->table('users')->create_row(array(
                                                                'name' => 'dan',
                                                           ));

        $this->mcfaddens = $this->db->table('events')->create_row(array(
                                                                       'name' => 'mcfaddens',
                                                                  ));

        $this->shadowroom = $this->db->table('events')->create_row(array(
                                                                        'name' => 'shadowroom',
                                                                   ));

        $this->public = $this->db->table('events')->create_row(array(
                                                                    'name' => 'public',
                                                               ));

        $this->venkat_mcfaddens_checkin = $this->db->table('checkins')->create_row(array(
                                                                                        'user_id' => $this->venkat->id,
                                                                                        'event_id' => $this->mcfaddens->id,
                                                                                   ));

        $this->venkat_public_checkin = $this->db->table('checkins')->create_row(array(
                                                                                        'user_id' => $this->venkat->id,
                                                                                        'event_id' => $this->public->id,
                                                                                   ));

        $this->stanford = $this->db->table('networks')->create_row(array(
                                                                       'name' => 'stanford',
                                                                   ));

        $this->maryland = $this->db->table('networks')->create_row(array(
                                                                       'name' => 'maryland',
                                                                   ));

        $this->gwu = $this->db->table('networks')->create_row(array(
                                                                  'name' => 'gwu',
                                                              ));

        $this->cornell = $this->db->table('networks')->create_row(array(
                                                                      'name' => 'cornell',
                                                                  ));

        $this->db->table('user_networks')->create_row(array(
                                                          'user_id' => $this->venkat->id,
                                                          'network_id' => $this->stanford->id,
                                                      ));

        $this->db->table('user_networks')->create_row(array(
                                                          'user_id' => $this->venkat->id,
                                                          'network_id' => $this->maryland->id,
                                                      ));

        $this->db->table('user_networks')->create_row(array(
                                                          'user_id' => $this->dan->id,
                                                          'network_id' => $this->stanford->id,
                                                      ));

        $this->db->table('user_networks')->create_row(array(
                                                          'user_id' => $this->dan->id,
                                                          'network_id' => $this->cornell->id,
                                                      ));

        $this->db->table('user_networks')->create_row(array(
                                                          'user_id' => $this->dan->id,
                                                          'network_id' => $this->gwu->id,
                                                      ));
    }

    function test_local_foreign_key()
    {
        $this->assert_equal($this->venkat_mcfaddens_checkin->user->name, 'venkat');
        $this->assert_equal($this->venkat_mcfaddens_checkin->event->name, 'mcfaddens');
    }

    function test_foreign_key_by_table_name()
    {
        $names = array();
//        foreach ($this->venkat->checkins as $checkin) {
//            $names[] = $checkin->event->name;
//        }
//        sort($names);
        $this->assert_equal(implode(',', $names), 'mcfaddens,public');
    }

    /*
    function test_foreign_key_with_joining_table()
    {
        $venkats_networks = array();
        foreach ($this->venkat->networks as $network) {
            $venkats_networks[] = $network->name;
        }
        sort($venkats_networks);

        $this->assert_equal(implode(',', $venkats_networks), 'maryland,stanford');
    }
    */

}
