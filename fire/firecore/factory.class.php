<?php

class Factory
{

    /**
     * @var ConfigSource
     */
    protected $config_source;


    /**
     * @var Index
     */
    protected $index;

    /**
     * @var ClassLoader
     */
    protected $class_loader;

    protected $config;

    function __construct(ConfigSource $config_source, Index $index, ClassLoader $class_loader, $config_name)
    {
        $this->config_source = $config_source;
        $this->index = $index;
        $this->class_loader = $class_loader;
        $this->config = $this->load_config($config_name);
    }

    function build($key)
    {
        if (!$this->class_loader->fetch($key)) {
            $class_config = $this->config[$key];
            $class = $class_config['type'];

            $args = $this->get_constructor_arguments($class, $class_config);
            $this->class_loader->create($key, $class, $args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
        }

        return $this->class_loader->fetch($key);
    }

    private function get_constructor_arguments($class, $config)
    {
        $class_info = $this->index->get_resource_metadata($class);
        if (!isset($class_info['methods']['__construct']))
            return array();

        $params = $class_info['methods']['__construct']['arguments'];
        $args = array_fill(0, 6, null);

        if (count($params) == 1 && isset($params['options'])) {
            return array($config);
        }

        foreach ($params as $arg_info) {
            $args[$arg_info['position']] = $config[$arg_info['name']];
        }

        return $args;
    }

    private function load_config($config_name)
    {
        return $this->config_source->load($config_name);
    }

}
