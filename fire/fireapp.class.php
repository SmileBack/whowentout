<?php

class FireApp
{

    /**
     * @var ClassLoader
     */
    private $class_loader;

    function __construct($class_loader)
    {
        $this->class_loader = $class_loader;
    }

    /**
     * @return ClassLoader
     */
    function class_loader()
    {
        return $this->class_loader;
    }

    function enable_autoload()
    {
        $this->class_loader->enable_autoload();
    }

}
