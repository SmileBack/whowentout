<?php

class InvitePackage extends Package
{

    public $version = '0.2.4';

    function install()
    {
        $this->database->create_table('invites', array(
            'id' => array('type' => 'id'),
            'sender_id' => array('type' => 'integer'),
            'receiver_id' => array('type' => 'integer'),
            'event_id' => array('type' => 'integer'),
            'created_at' => array('type' => 'time'),
            'status' => array('type' => 'string'),
        ));

        $this->database->table('invites')->create_unique_index('sender_id', 'receiver_id', 'event_id');

        $this->database->table('invites')->create_foreign_key('sender_id', 'users', 'id');
        $this->database->table('invites')->create_foreign_key('receiver_id', 'users', 'id');
        $this->database->table('invites')->create_foreign_key('event_id', 'events', 'id');
    }

    function update_0_2_3()
    {
        $this->database->table('invites')->create_column('status', array('type' => 'string'));
        $this->database->execute("UPDATE invites set status = :status", array('status' => 'pending'));
    }

    function update_0_2_4()
    {
        $this->database->execute("UPDATE invites SET status = 'accepted' WHERE
        	(SELECT COUNT(*) FROM checkins
        		WHERE invites.event_id = checkins.event_id
        		AND invites.receiver_id = checkins.user_id) > 0");
    }

}
