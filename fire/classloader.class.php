<?php

class ClassLoader
{

    /**
     * @var Index
     */
    private $index;

    function __construct(Index $index)
    {
        $this->index = $index;
    }

    function init($class_name, $arg1 = NULL, $arg2 = NULL, $arg3 = NULL)
    {
        $this->load($class_name);
        return new $class_name($arg1, $arg2, $arg3);
    }

    function init_subclass($superclass, $subclass, $arg1 = NULL, $arg2 = NULL, $arg3 = NULL)
    {
        if ($this->is_subclass($superclass, $subclass))
            return $this->init($subclass, $arg1, $arg2, $arg3);
        elseif ($this->is_subclass($superclass, "$subclass$superclass"))
            return $this->init("$subclass$superclass", $arg1, $arg2, $arg3);
        else
            return NULL;
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
            return NULL;

        $file_metadata = $this->index->get_resource_metadata($class_metadata['file']);
        return $file_metadata['filepath'];
    }

}
