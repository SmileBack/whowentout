<?php

abstract class Driver
{

    private $name;
    protected $config;

    function __construct($config = array())
    {
        $this->config = $config;
    }

    function name()
    {
        if (!$this->name) {
            $parent_class = strtolower(get_parent_class($this));
            $this_class = strtolower(get_class($this));
            return preg_replace("/$parent_class$/", '', $this_class);
        }
    }

}
