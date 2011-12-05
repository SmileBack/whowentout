<?php

class Event_Tests extends TestGroup
{

    /**
     * @var Database
     */
    private $db;

    /**
     * @var PackageInstaller
     */
    private $installer;

    /**
     * @var WhoWentOutApp
     */
    private $app;



    function setup()
    {
        $factory = factory('event_tests', array(
                                               'package_installer' => array(
                                                   'type' => 'PackageInstaller',
                                                   'database' => 'database',
                                                   'class_loader' => 'class_loader',
                                               ),
                                               'database' => array(
                                                   'type' => 'Database',
                                                   'host' => 'localhost',
                                                   'database' => 'fire_test',
                                                   'username' => 'root',
                                                   'password' => 'root',
                                               ),
                                          ));
        
        $this->db = $factory->build('database');
        $this->drop_all_tables();

        $this->installer = $factory->build('package_installer');
        $this->installer->install('whowentoutdatabase');
    }

    function drop_all_tables()
    {
        foreach ($this->db->list_table_names() as $table_name)
            $this->db->destroy_table($table_name);
    }

    function test_create_basic_event()
    {
        $place = $this->app->places->create(array(
                                                 'name' => 'McFaddens',
                                            ));

        $event = $this->app->events->create(array(
                                                 'name' => 'Tuesday Party',
                                                 'place' => $place,
                                                 'date' => '2011-12-13',
                                            ));


        $found_event = $this->app->events->first(array(
                                                      'name' => 'Tuesday Party',
                                                 ));

        $this->assert_equal($event, $found_event, 'only single reference for a particular event object');
        $this->assert_equal($event->id, $found_event->id, 'event');
    }

    function test_update_event()
    {
        $place = $this->app->places->create(array('name' => 'Public'));
        $other_place = $this->app->places->create(array('name' => 'Other Place'));

        $event = $this->app->events->create(array(
                                                'name' => 'Public Party',
                                                'place' => $place,
                                                'date' => '2011-12-13',
                                            ));
        
        $event->place = $other_place;
        $event->save();
        $this->assert_equal($event->place, $other_place);
        $this->assert_equal($event->place->id, $other_place->id);
    }
    
}