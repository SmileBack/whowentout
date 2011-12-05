<?php

class r
{
    
    public static function __callStatic($view_name, $args)
    {
        $vars = isset($args[0]) ? $args[0] : array();

        $view = new Template($view_name);
        $view->set($vars);

        return $view->render();
    }
    
}
