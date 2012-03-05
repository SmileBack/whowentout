<?php

class TestAction extends Action
{

    /**
     * @var Database
     */
    private $database;

    function execute()
    {
        db()->execute('DELIMITER ;;');
    }

}
