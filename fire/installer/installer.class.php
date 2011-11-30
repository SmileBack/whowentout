<?php

abstract class Installer
{

    /**
     * @var Database
     */
    protected $db;

    function __construct(Database $db)
    {
        $this->db = $db;
    }

    function _create_table_if_needed()
    {
        
    }
    
}
