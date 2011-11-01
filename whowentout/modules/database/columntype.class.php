<?php

abstract class ColumnType
{

    protected $options = array();

    function __construct($options)
    {
        $this->options = $options;
    }

    abstract function to_sql();

    

}
