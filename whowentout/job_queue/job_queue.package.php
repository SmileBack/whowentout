<?php

class JobQueuePackage extends Package
{
    public $version = '0.2.1';

    function install()
    {
        $this->database->create_table('jobs', array(
            'id' => array('type' => 'key'),
            'type' => array('type' => 'string'),
            'status' => array('type' => 'string'),
            'options' => array('type' => 'text'),
            'created_at' => array('type' => 'time'),
            'completed_at' => array('type' => 'time'),
        ));

        $this->database->table('jobs')->create_index('type');
        $this->database->table('jobs')->create_index('status');
    }

    function update_0_2_1()
    {
        $this->database->table('jobs')->create_column('created_at', array(
            'type' => 'time',
        ));
        $this->database->table('jobs')->create_column('completed_at', array(
            'type' => 'time',
        ));
    }

}
