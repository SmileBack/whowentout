<?php

function r($view_name, $vars = array())
{
    $args = func_get_args();
    $view_name = $args[0];
    $vars = isset($args[1]) ? (array)$args[1] : array();

    $view = new Template($view_name);
    $view->set($vars);
    
    return $view->render();
}
