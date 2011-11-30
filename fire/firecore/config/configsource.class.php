<?php

class ConfigSource
{
    
    /**
     * @var Index
     */
    private $index;

    private $config = array();

    function __construct(Index $index)
    {
        $this->index = $index;
    }

    function load($config_name)
    {
        if ( ! isset($this->config[$config_name]) ) {
            $metadata = $this->index->get_resource_metadata("$config_name.yml");
            if ($metadata) {
                $this->config[$config_name] = Spyc::YAMLLoad($metadata['filepath']);
            }
            else {
                $this->config[$config_name] = null;
            }
        }

        return $this->config[$config_name];
    }

}
