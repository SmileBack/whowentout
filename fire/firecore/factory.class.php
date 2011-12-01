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
        
        $this->class_loader->register('config_source', $config_source);
        $this->class_loader->register('class_loader', $class_loader);

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

    function register($key, $object)
    {
        $this->class_loader->register($key, $object);
    }

    private function get_constructor_arguments($class, $config)
    {
        $class_info = $this->class_loader->get_class_metadata($class);
        if (!isset($class_info['methods']['__construct']))
            return array();

        $params = $class_info['methods']['__construct']['arguments'];
        $args = array_fill(0, 6, null);

        if (count($params) == 1 && isset($params['options'])) {
            return array($config);
        }

        foreach ($params as $arg_info) {
            $arg_position = $arg_info['position'];
            $arg_value = $config[ $arg_info['name'] ];

            //this argument references an object that should be (or has already been) built
            $arg_type = isset($arg_info['type']) ? $arg_info['type'] : null;
            if ($arg_type)
                $arg_value = $this->build($arg_value);

            $args[$arg_position] = $arg_value;
        }

        return $args;
    }

    private function load_config($config_name)
    {
        return $this->config_source->load($config_name);
    }

}
