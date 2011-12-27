<?php

class JobQueuePackage extends Package
{
    public $version = '0.2';

    function install()
    {
        $this->database->create_table('jobs', array(
            'id' => array('type' => 'key'),
            'type' => array('type' => 'string'),
            'options' => array('type' => 'text'),
        ));

        $this->database->table('jobs')->create_index('type');
    }

}