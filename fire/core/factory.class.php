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
        if (!$this->class_loader->fetch($key) && isset($this->config[$key])) {
            $class_config = $this->config[$key];
            $class = $class_config['type'];

            $args = $this->get_constructor_arguments($class, $class_config);
            $this->class_loader->create($key, $class, $args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
        }
        
        //factory exists for the item
        elseif (isset($this->config[$key . '_factory'])) {
            $specialized_factory = $this->build($key . '_factory');
            $args = array_slice(func_get_args(), 1);
            $instance = call_user_func_array(array($specialized_factory, 'build'), $args);
            $this->class_loader->register($key, $instance);
        }

        return $this->class_loader->fetch($key);
    }

    function register($key, $object)
    {
        $this->class_loader->register($key, $object);
    }
    
    private function get_constructor_arguments($class, $config)
    {
        $args = array_fill(0, 6, null);
        
        $reflector = new ReflectionClass($class);
        $constructor = $reflector->getConstructor();
        
        if (!$constructor)
            return $args;
        
        $params = $constructor->getParameters();

        if (count($params) == 1 && $params[0]->getName() == 'options') {
            $args[0] = $config;
            return $args;
        }

        /* @var $param ReflectionParameter */
        foreach ($params as $param) {
            $arg_position = $param->getPosition();
            $arg_value = $config[ $param->getName() ];
            
            $arg_class = $param->getClass();
            if ($arg_class)
                $arg_value = $this->build($arg_value);

            $args[$arg_position] = $arg_value;
        }

        return $args;
    }

    private function load_config($config_name)
    {
        if (is_array($config_name))
            return $config_name;
        
        return $this->config_source->load($config_name);
    }

}
