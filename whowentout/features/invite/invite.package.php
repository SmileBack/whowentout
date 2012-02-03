<?php

class InvitePackage extends Package
{

    public $version = '0.2.2';

    function install()
    {
        $this->database->create_table('invites', array(
            'id' => array('type' => 'id'),
            'sender_id' => array('type' => 'integer'),
            'receiver_id' => array('type' => 'integer'),
            'event_id' => array('type' => 'integer'),
            'created_at' => array('type' => 'time'),
        ));

        $this->database->table('invites')->create_unique_index('sender_id', 'receiver_id', 'event_id');

        $this->database->table('invites')->create_foreign_key('sender_id', 'users', 'id');
        $this->database->table('invites')->create_foreign_key('receiver_id', 'users', 'id');
        $this->database->table('invites')->create_foreign_key('event_id', 'events', 'id');
    }

}
