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
        krumo::dump($this->index->data());
    }

    function get_class_metadata($class_name)
    {
        $class_name = strtolower($class_name);
        return $this->index->get_resource_metadata("$class_name class");
    }

}
