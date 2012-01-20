<?php

class PageFlow
{


    private $actions = array();

    function __construct()
    {

    }

    public function add_action(Action $action)
    {
        $action_name = get_class($action);
        $this->actions[$action_name] = $action;
    }

}

