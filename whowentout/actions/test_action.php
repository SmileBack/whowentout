<?php

class TestAction extends Action
{

    /**
     * @var Database
     */
    private $database;

    function execute()
    {
        apc_add('woo', 'foo');
    }

    function url()
    {
        $protocol = 'http';
        return $protocol . "://" . $_SERVER['HTTP_HOST'] . '/';
    }


}
