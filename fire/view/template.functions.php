<?php

class r
{
    
    public static function __callStatic($view_name, $args)
    {
        $vars = isset($args[0]) ? $args[0] : array();

        $class_loader = app()->class_loader();

        $display_class = $class_loader->get_subclass_name('Display', $view_name);
        if (!$display_class)
            $display_class = 'Display';

        $display = $class_loader->init($display_class, $view_name);
        $display->set($vars);
        
        return $display;
    }
    
}
