<?php

class Factory
{

    /**
     * @var ConfigSource
     */
    protected $config_source;

    /**
     * @var ClassLoader
     */
    protected $class_loader;

    protected $config;

    function __construct(ConfigSource $config_source, ClassLoader $class_loader, $config_name)
    {
        $this->config_source = $config_source;
        $this->class_loader = $class_loader;
        $this->config = $this->load_config($config_name);
    }

    function build($key)
    {
        if ( ! $this->class_loader->fetch($key)) {
            $class_config = $this->config[$key];
            $class = $class_config['type'];
            $this->class_loader->create($key, $class, $class_config);
        }

        return $this->class_loader->fetch($key);
    }

    private function load_config($config_name)
    {
        return $this->config_source->load($config_name);
    }

}
