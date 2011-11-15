<?php

class DatabaseRow
{

    private $values = array();
    private $changes = array();
    
    /**
     * @var DatabaseTable
     */
    private $table;

    private $id;

    function __construct(DatabaseTable $table, $id)
    {
        $this->table = $table;
        $this->id = $id;
    }

    function changes()
    {
        return $this->changes;
    }

    function __get($field)
    {
        if (isset($this->changes[$field]))
            return $this->changes[$field];
        elseif (isset($this->values[$field]))
            return $this->values[$field];
        else
            return NULL;
    }

    function __set($field, $value)
    {
        $this->changes[$field] = $value;
    }

    function save()
    {
        
    }

}
