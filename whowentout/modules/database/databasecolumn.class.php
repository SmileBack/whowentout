<?php

abstract class DatabaseColumn
{

    /**
     * @var \DatabaseTable
     */
    private $table;
    protected $options = array();

    function __construct(DatabaseTable $table, $options)
    {
        $this->table = $table;
        $this->options = $options;
    }

    abstract function from_database_value($value);
    abstract function to_database_value($value);
    
}
