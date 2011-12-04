<?php

class WhoWentOutDatabase extends Package
{
    
    function install()
    {
        $this->database->create_table('users', array(
                                                 'id' => array('type' => 'id'),
                                                 'full_name' => array('type' => 'string'),
                                                 'email' => array('type' => 'email'),
                                               ));

        $this->database->create_table('places', array(
                                                  'id' => array('type' => 'id'),
                                                  'name' => array('type' => 'string'),
                                                ));

        $this->database->create_table('events', array(
                                                  'id' => array('type' => 'id'),
                                                  'name' => array('type' => 'string'),
                                                  'date' => array('type' => 'date'),
                                                  'place_id' => array('type' => 'integer'),
                                                ));
        
        $this->database->table('events')->create_foreign_key('place_id', 'places', 'id');

        $this->database->create_table('checkins', array(
                                                    'id' => array('type' => 'id'),
                                                    'user_id' => array('type' => 'integer'),
                                                    'event_id' => array('type' => 'integer'),
                                                    'time' => array('type' => 'time'),
                                                  ));

        $this->database->table('checkins')->create_foreign_key('user_id', 'users', 'id');
        $this->database->table('checkins')->create_foreign_key('event_id', 'events', 'id');
    }

    function uninstall()
    {
        $this->database->destroy_table('events');
        $this->database->destroy_table('checkins');
        $this->database->destroy_table('places');
        $this->database->destroy_table('users');
    }

}
