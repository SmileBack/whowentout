<?php

abstract class ServerChannelDriver
{

    protected $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    abstract function push($channel, $data);

    abstract function delete($channel);

    abstract function url($channel);

}
