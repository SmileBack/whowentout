<?php

class r
{
    
    public static function __callStatic($view_name, $args)
    {
        $vars = $args[0];

        $view = new Template($view_name);
        $view->set($vars);

        return $view->render();
    }
    
}
