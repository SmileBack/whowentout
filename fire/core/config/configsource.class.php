<?php

class ConfigSource
{
    
    /* @var Index */
    private $index;
    private $environment;

    private $config = null;

    function __construct(Index $index, $environment)
    {
        $this->index = $index;
        $this->environment = $environment;
    }

    function load()
    {
        if (!$this->config) {
            $config_file_name = "app.$this->environment.yml";

            /* @var $config_meta ConfigMetadata */
            $config_meta = $this->index->get_metadata($config_file_name);
            $this->config = $config_meta->data;
        }

        return $this->config;
    }

}
