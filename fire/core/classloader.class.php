<?php

class ClassLoader
{

    /**
     * @var Index
     */
    private $index;

    private $class_instances = array();

    function __construct(Index $index)
    {
        $this->index = $index;
    }

    function init($class_name, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null, $arg5 = null)
    {
        $this->load($class_name);
        $num_params = $this->get_num_constructor_parameters($class_name);

        if ($num_params == 0)
            return new $class_name;
        else if ($num_params == 1)
            return new $class_name($arg1);
        else if ($num_params == 2)
            return new $class_name($arg1, $arg2);
        else if ($num_params == 3)
            return new $class_name($arg1, $arg2, $arg3);
        else if ($num_params == 4)
            return new $class_name($arg1, $arg2, $arg3, $arg4);
        else if ($num_params == 5)
            return new $class_name($arg1, $arg2, $arg3, $arg4, $arg5);
        else
            throw new Exception("Greater than 5 arguments is currently unsupported");
    }

    function init_subclass($superclass, $subclass, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null, $arg5 = null)
    {
        $subclass_name = $this->get_subclass_name($superclass, $subclass);
        
        if ($subclass_name)
            return $this->init($subclass_name, $arg1, $arg2, $arg3, $arg4, $arg5);
        else
            return null;
    }

    function create($key, $class_name, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null, $arg5 = null)
    {
        $instance = $this->init($class_name, $arg1, $arg2, $arg3, $arg4, $arg5);
        $this->register($key, $instance);
        return $this->fetch($key);
    }

    function register($key, $object)
    {
        $this->class_instances[$key] = $object;
    }

    function fetch($key)
    {
        return isset($this->class_instances[$key])
                ? $this->class_instances[$key]
                : null;
    }

    function get_subclass_name($superclass, $subclass)
    {
        $candidate_subclass_names = array($subclass, "$subclass$superclass", "{$subclass}_{$superclass}");
        foreach ($candidate_subclass_names as $class_name) {
            if ( $this->is_subclass($superclass, $class_name) )
                return $class_name;
        }
        return null;
    }

    /**
     * @return Index
     */
    function get_index()
    {
        return $this->index;
    }

    function load($class_name)
    {
        $class_filepath = $this->get_class_filepath($class_name);

        if (!$class_filepath)
            return;

        require_once $class_filepath;
    }

    function enable_autoload()
    {
        spl_autoload_register(array($this, 'load'));
    }

    function is_subclass($superclass, $subclass)
    {
        $subclass_names = $this->get_subclass_names($superclass);
        $subclass_names = array_map('strtolower', $subclass_names);
        return in_array(strtolower($subclass), $subclass_names);
    }

    function get_subclass_names($superclass)
    {
        $superclass_metadata = $this->get_class_metadata($superclass);

        if (!$superclass_metadata)
            return array();

        if (!isset($superclass_metadata['subclasses']))
            return array();

        return $superclass_metadata['subclasses'];
    }

    function get_class_metadata($class_name)
    {
        $class_name = strtolower($class_name);
        return $this->index->get_resource_metadata("$class_name class");
    }

    private function get_class_filepath($class_name)
    {
        $class_metadata = $this->get_class_metadata($class_name);
        if (!$class_metadata)
            return null;

        $file_metadata = $this->index->get_resource_metadata($class_metadata['file']);
        return $file_metadata['filepath'];
    }

    private function get_num_constructor_parameters($class)
    {
        $reflector = new ReflectionClass($class);
        $constructor = $reflector->getConstructor();

        if (!$constructor)
            return 0;

        $params = $constructor->getParameters();
        return count($params);
    }

}
