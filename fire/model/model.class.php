<?php

class Model
{

    /**
     * @var DatabaseRow
     */
    private $row;

    function __construct(DatabaseRow $row)
    {
        $this->row = $row;
    }

    function __get($name)
    {
        return $this->row->$name;
    }

    function __set($name, $value)
    {
        $this->row->$name = $value;
    }

    function related_model_collection($model)
    {
        
    }

    function related_model($model)
    {
        
    }

}
