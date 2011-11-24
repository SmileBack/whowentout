<?php

abstract class DatabaseColumn
{

    /**
     * @var \DatabaseTable
     */
    private $table;
    protected $options = array();
    protected $required_options = array('name', 'type');

    function __construct(DatabaseTable $table, $options)
    {
        $this->table = $table;
        $this->options = $options;

        check_required_options($options, $this->required_options);
    }

    function name()
    {
        return $this->options['name'];
    }

    abstract function from_database_value($value);
    abstract function to_database_value($value);
    
}
