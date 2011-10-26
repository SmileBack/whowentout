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

    function get_subclass_names($superclass)
    {
        $superclass_metadata = $this->get_class_metadata($superclass);

        if ( ! $superclass_metadata )
            return array();

        if ( ! isset($superclass_metadata['subclasses']) )
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
