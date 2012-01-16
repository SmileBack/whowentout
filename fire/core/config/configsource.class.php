<?php

class ConfigSource
{
    
    /**
     * @var Index
     */
    private $index;

    private $config = null;

    function __construct(Index $index)
    {
        $this->index = $index;
    }

    function load()
    {
        if (!$this->config) {

        }

        return $this->config;
    }

}
