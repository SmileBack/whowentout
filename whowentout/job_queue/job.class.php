<?php

abstract class Job
{

    public $options;

    public $required_options = array();

    function __construct($options = array())
    {
        $this->options = $options;
        check_required_options($this->options, $this->required_options);
    }

    abstract function run();
}
