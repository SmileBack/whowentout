<?php

class WhoWentOut extends Package
{

    public $version = '0.1.9';

    function install()
    {
        $this->create_users_table();
        $this->create_places_table();
        $this->create_events_table();
        $this->create_checkins_table();
        $this->create_profile_pictures_table();
        $this->create_networks_table();
    }

    function create_users_table()
    {
        $this->database->create_table('users', array(
                                                    'id' => array('type' => 'id'),
                                                    'first_name' => array('type' => 'string'),
                                                    'last_name' => array('type' => 'string'),
                                                    'email' => array('type' => 'string'),
                                                    'facebook_id' => array('type' => 'string'),
                                                    'hometown' => array('type' => 'string'),
                                                    'gender' => array('type' => 'string'),
                                               ));
        $this->database->table('users')->create_unique_index('facebook_id');
    }

    function create_places_table()
    {
        $this->database->create_table('places', array(
                                                     'id' => array('type' => 'id'),
                                                     'name' => array('type' => 'string'),
                                                ));
    }

    function create_events_table()
    {
        $this->database->create_table('events', array(
                                                     'id' => array('type' => 'id'),
                                                     'name' => array('type' => 'string'),
                                                     'date' => array('type' => 'date'),
                                                     'deal' => array('type' => 'text'),
                                                     'place_id' => array('type' => 'integer'),
                                                ));

        $this->database->table('events')->create_foreign_key('place_id', 'places', 'id');
    }

    function create_checkins_table()
    {
        $this->database->create_table('checkins', array(
                                                       'id' => array('type' => 'id'),
                                                       'user_id' => array('type' => 'integer'),
                                                       'event_id' => array('type' => 'integer'),
                                                       'time' => array('type' => 'time'),
                                                  ));

        $this->database->table('checkins')->create_foreign_key('user_id', 'users', 'id');
        $this->database->table('checkins')->create_foreign_key('event_id', 'events', 'id');
    }

    function create_profile_pictures_table()
    {
        $this->database->create_table('profile_pictures', array(
                                                               'id' => array('type' => 'id'),
                                                               'user_id' => array('type' => 'integer'),
                                                               'version' => array('type' => 'integer'),
                                                               'crop_x' => array('type' => 'integer'),
                                                               'crop_y' => array('type' => 'integer'),
                                                               'crop_width' => array('type' => 'integer'),
                                                               'crop_height' => array('type' => 'integer'),
                                                          ));

        $this->database->table('profile_pictures')->create_foreign_key('user_id', 'users', 'id');
    }

    function create_networks_table()
    {
        $this->database->create_table('networks', array(
                                                       'id' => array(
                                                           'type' => 'id',
                                                           'auto_increment' => false,
                                                       ),
                                                       'type' => array('type' => 'string'),
                                                       'name' => array('type' => 'string'),
                                                  ));
        $this->database->table('networks')->create_index('type');

        $this->database->create_table('user_networks', array(
                                                            'user_id' => array('type' => 'integer'),
                                                            'network_id' => array('type' => 'integer'),
                                                       ));
        $this->database->table('user_networks')->create_foreign_key('user_id', 'users', 'id');
        $this->database->table('user_networks')->create_foreign_key('network_id', 'networks', 'id');
    }

    function update_0_1_1()
    {
        $this->database->table('events')->create_column('deal', array('type' => 'text'));
    }

    /**
     * Create facebook_id column
     */
    function update_0_1_2()
    {
        $this->database->table('users')->create_column('facebook_id', array(
                                                                           'type' => 'string',
                                                                      ));
        $this->database->table('users')->create_unique_index('facebook_id');
    }

    /**
     * Split full name column into first and last name
     */
    function update_0_1_3()
    {
        $this->database->table('users')->destroy_column('full_name');
        $this->database->table('users')->create_column('first_name', array('type' => 'string'));
        $this->database->table('users')->create_column('last_name', array('type' => 'string'));
    }

    /**
     * Added date of birth
     */
    function update_0_1_4()
    {
        $this->database->table('users')->create_column('date_of_birth', array('type' => 'date'));
    }

    /**
     * Added hometown
     */
    function update_0_1_5()
    {
        $this->database->table('users')->create_column('hometown', array('type' => 'string'));
    }


    /**
     * Created checkins table
     */
    function update_0_1_6()
    {
        $this->database->create_table('checkins', array(
                                                       'id' => array('type' => 'id'),
                                                       'time' => array('type' => 'time'),
                                                       'event_id' => array('type' => 'integer', 'null' => false),
                                                       'user_id' => array('type' => 'integer', 'null' => false),
                                                  ));

        $this->database->table('checkins')->create_unique_index('event_id', 'user_id');

        $this->database->table('checkins')->create_foreign_key('event_id', 'events', 'id');
        $this->database->table('checkins')->create_foreign_key('user_id', 'users', 'id');
    }

    /**
     * Add gender column
     */
    function update_0_1_7()
    {
        $this->database->table('users')->create_column('gender', array('type' => 'string'));
    }

    /**
     * Create profile pictures table
     */
    function update_0_1_8()
    {
        $this->database->create_table('profile_pictures', array(
                                                               'id' => array('type' => 'id'),
                                                               'user_id' => array('type' => 'integer'),
                                                               'version' => array('type' => 'integer'),
                                                               'crop_x' => array('type' => 'integer'),
                                                               'crop_y' => array('type' => 'integer'),
                                                               'crop_width' => array('type' => 'integer'),
                                                               'crop_height' => array('type' => 'integer'),
                                                          ));

        $this->database->table('profile_pictures')->create_foreign_key('user_id', 'users', 'id');
    }

    /**
     * Create networks table
     */
    function update_0_1_9()
    {
        $this->database->create_table('networks', array(
                                                       'id' => array(
                                                           'type' => 'id',
                                                           'auto_increment' => false,
                                                       ),
                                                       'type' => array('type' => 'string'),
                                                       'name' => array('type' => 'string'),
                                                  ));
        $this->database->table('networks')->create_index('type');

        $this->database->create_table('user_networks', array(
                                                            'user_id' => array('type' => 'integer'),
                                                            'network_id' => array('type' => 'integer'),
                                                       ));
        $this->database->table('user_networks')->create_foreign_key('user_id', 'users', 'id');
        $this->database->table('user_networks')->create_foreign_key('network_id', 'networks', 'id');
    }

    function uninstall()
    {
        $this->database->destroy_table('networks');
        $this->database->destroy_table('profile_pictures');
        $this->database->destroy_table_if_exists('checkins');
        $this->database->destroy_table_if_exists('events');
        $this->database->destroy_table_if_exists('places');
        $this->database->destroy_table_if_exists('users');
    }

}
