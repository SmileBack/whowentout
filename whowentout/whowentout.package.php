<?php

class WhoWentOutPackage extends Package
{

    public $version = '0.3.7';

    function install()
    {
        $this->create_users_table();
        $this->create_user_friends_table();
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
            'facebook_profile_last_update' => array('type' => 'time'),
            'facebook_friends_last_update' => array('type' => 'time'),
            'hometown' => array('type' => 'string'),
            'gender' => array('type' => 'string'),
            'date_of_birth' => array('type' => 'date'),
            'cell_phone_number' => array('type' => 'string'),
            'college_networks' => array('type' => 'string'),
        ));
        $this->database->table('users')->create_unique_index('facebook_id');
    }

    function create_places_table()
    {
        $this->database->create_table('places', array(
            'id' => array('type' => 'id'),
            'name' => array('type' => 'string'),
            'type' => array('type' => 'string'),
        ));

        $this->database->table('places')->create_index('type');
    }

    function create_events_table()
    {
        $this->database->create_table('events', array(
            'id' => array('type' => 'id'),
            'name' => array('type' => 'string'),
            'date' => array('type' => 'date'),
            'deal' => array('type' => 'text'),
            'deal_type' => array('type' => 'text'),
            'place_id' => array('type' => 'integer'),
            'user_id' => array('type' => 'integer'),
            'priority' => array('type' => 'integer'),
        ));

        $this->database->table('events')->create_foreign_key('place_id', 'places', 'id');
        $this->database->table('events')->create_foreign_key('user_id', 'users', 'id');
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
        $this->database->table('proflie_pictures')->create_unique_index('user_id');
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

    function update_0_2_9()
    {
        $this->database->table('events')->create_column('user_id', array(
            'type' => 'integer',
        ));
        $this->database->table('events')->create_foreign_key('user_id', 'users', 'id');
    }

    function update_0_3_0()
    {
        $this->database->table('places')->create_column('type', array(
            'type' => 'string',
        ));
        $this->database->execute('UPDATE places SET type = :type', array('type' => 'other'));
    }

    function update_0_3_1()
    {
        $this->database->table('users')->create_column('college_networks', array('type' => 'string'));
    }

    function update_0_3_2()
    {
        $this->database->table('events')->create_column('deal_type', array('type' => 'string'));
    }

    function update_0_3_3()
    {
//        $this->create_user_friends_table();
    }

    function update_0_3_4()
    {
        $this->database->table('events')->create_column('priority', array('type' => 'integer'));
    }

    function update_0_3_5()
    {
        $this->database->table('users')->create_column('facebook_profile_last_update', array('type' => 'time'));
    }

    function update_0_3_6()
    {
        $this->database->table('events')->create_column('count', array(
            'type' => 'integer',
            'default' => 0,
        ));
        $this->database->table('events')->create_index('count');

        $this->database->execute("UPDATE events SET count = (SELECT COUNT(*) AS count
                                    FROM checkins WHERE checkins.event_id = events.id)");
    }

    function update_0_3_7()
    {
        $this->database->execute('CREATE TRIGGER update_event_count_after_checkin AFTER INSERT ON checkins
          FOR EACH ROW
            UPDATE events
        	 	SET count = (SELECT COUNT(*) FROM checkins WHERE checkins.event_id = events.id)
        	 	WHERE id = NEW.event_id;');

        $this->database->execute('CREATE TRIGGER update_event_count_before_switch BEFORE DELETE ON checkins
          FOR EACH ROW
            UPDATE events
        	 	SET count = (SELECT (COUNT(*)-1) AS count FROM checkins WHERE checkins.event_id = events.id)
        	 	WHERE id = OLD.event_id;');
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
