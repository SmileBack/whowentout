<?php

class WhoWentOutApp extends FireApp
{

    /**
     * @var \Database
     */
    protected $database;

    function __construct(ClassLoader $class_loader, Database $database)
    {
        parent::__construct($class_loader, $database);
        $this->database = $database;
    }
    
}
