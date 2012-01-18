<?php

class WhoWentOutPackage extends Package
{

    public $version = '0.2.8';

    function install()
    {
        $this->create_users_table();
        $this->create_places_table();
        $this->create_events_table();
        $this->create_profile_pictures_table();
        $this->create_networks_table();
    }

    function create_users_table()
    {
        $this->database->create_table('users', array(
            'id' => array('type' => 'id'),
            'last_login' => array('type' => 'time'),
            'first_name' => array('type' => 'string'),
            'last_name' => array('type' => 'string'),
            'email' => array('type' => 'string'),
            'facebook_id' => array('type' => 'string'),
            'facebook_access_token' => array('type' => 'string', 'length' => 512),
            'facebook_friends_last_update' => array('type' => 'time'),
            'hometown' => array('type' => 'string'),
            'gender' => array('type' => 'string'),
            'date_of_birth' => array('type' => 'date'),
            'cell_phone_number' => array('type' => 'string'),
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
            'id' => array('type' => 'id', 'auto_increment' => false, 'length' => 'big'),
            'type' => array('type' => 'string'),
            'name' => array('type' => 'string'),
        ));
        $this->database->table('networks')->create_index('type');

        $this->database->create_table('user_networks', array(
            'id' => array('type' => 'id'),
            'user_id' => array('type' => 'integer'),
            'network_id' => array('type' => 'bigint'),
        ));

        $this->database->table('user_networks')->create_foreign_key('user_id', 'users', 'id');
        $this->database->table('user_networks')->create_foreign_key('network_id', 'networks', 'id');
        $this->database->table('user_networks')->create_unique_index('user_id', 'network_id');
    }

    function create_user_friends_table()
    {
        $this->database->create_table('user_friends', array(
            'id' => array('type' => 'id'),
            'user_id' => array('type' => 'integer'),
            'friend_id' => array('type' => 'integer'),
        ));

        $this->database->table('user_friends')->create_unique_index('user_id', 'friend_id');

        $this->database->table('user_friends')->create_foreign_key('user_id', 'users', 'id');
        $this->database->table('user_friends')->create_foreign_key('friend_id', 'users', 'id');
    }

    function update_0_2_3()
    {
        $this->database->create_table('user_friends', array(
            'id' => array('type' => 'id'),
            'user_id' => array('type' => 'integer'),
            'friend_id' => array('type' => 'integer'),
        ));

        $this->database->table('user_friends')->create_unique_index('user_id', 'friend_id');

        $this->database->table('user_friends')->create_foreign_key('user_id', 'users', 'id');
        $this->database->table('user_friends')->create_foreign_key('friend_id', 'users', 'id');
    }

    function update_0_2_5()
    {
        $this->database->table('users')->create_column('cell_phone_number', array(
            'type' => 'string'
        ));
    }

    function update_0_2_6()
    {
        $this->database->table('users')->create_column('last_login', array(
            'type' => 'time',
        ));
    }

    function update_0_2_7()
    {
        $this->database->table('users')->create_column('facebook_access_token', array(
            'type' => 'string',
            'length' => 255,
        ));
    }

    function update_0_2_8()
    {
        $this->database->table('users')->create_column('facebook_friends_last_update', array(
            'type' => 'time',
        ));
    }

    function uninstall()
    {
        $this->database->destroy_table('networks');
        $this->database->destroy_table('profile_pictures');
        $this->database->destroy_table_if_exists('events');
        $this->database->destroy_table_if_exists('places');
        $this->database->destroy_table_if_exists('users');
    }

}
