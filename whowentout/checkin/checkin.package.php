<?php

class CheckinPackage extends Package
{

    public $version = '0.2';

    function install()
    {
        $this->database->create_table('checkins', array(
            'id' => array('type' => 'id'),
            'user_id' => array('type' => 'integer'),
            'event_id' => array('type' => 'integer'),
            'time' => array('type' => 'time'),
        ));

        $this->database->table('checkins')->create_foreign_key('user_id', 'users', 'id');
        $this->database->table('checkins')->create_foreign_key('event_id', 'events', 'id');
        $this->database->table('checkins')->create_unique_index('user_id', 'event_id');
    }

    function uninstall()
    {
        $this->database->destroy_table('checkins');
    }

}
