<?php

class EntouragePackage extends Package
{
    public $version = '0.1.0';

    function install()
    {
        $this->create_entourage_requests_table();
        $this->create_entourage_table();
    }

    function uninstall()
    {
        $this->destroy_entourage_requests_table();
        $this->destroy_entourage_table();
    }

    function create_entourage_requests_table()
    {
        $this->database->create_table('entourage_requests', array(
            'id' => array('type' => 'id'),
            'sender_id' => array('type' => 'integer'),
            'receiver_id' => array('type' => 'integer'),
            'status' => array('type' => 'string'),
        ));

        $this->database->table('entourage_requests')->create_unique_index('sender_id', 'receiver_id');
        $this->database->table('entourage_requests')->create_index('status');

        $this->database->table('entourage_requests')->create_foreign_key('sender_id', 'users', 'id');
        $this->database->table('entourage_requests')->create_foreign_key('receiver_id', 'users', 'id');
    }

    function destroy_entourage_requests_table()
    {
        $this->database->destroy_table_if_exists('entourage_invites');
    }

    function create_entourage_table()
    {
        $this->database->create_table('entourage', array(
            'id' => array('type' => 'id'),
            'user_id' => array('type' => 'integer'),
            'friend_id' => array('type' => 'integer'),
        ));

        $this->database->table('entourage')->create_unique_index('user_id', 'friend_id');

        $this->database->table('entourage')->create_foreign_key('user_id', 'users', 'id');
        $this->database->table('entourage')->create_foreign_key('friend_id', 'users', 'id');
    }

    function destroy_entourage_table()
    {
        $this->database->destroy_table_if_exists('entourage');
    }

}
