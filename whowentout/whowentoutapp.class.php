<?php

class WhoWentOutApp extends FireApp
{

    /**
     * @var \Database
     */
    protected $database;

    /**
     * @var ModelCollection
     */
    private $places;

    function __construct(ClassLoader $class_loader, Database $database)
    {
        parent::__construct($class_loader, $database);
        $this->database = $database;

        $this->places = new ModelCollection($this->database()->table('places'));
    }

    function places()
    {
        return $this->places;
    }

}
