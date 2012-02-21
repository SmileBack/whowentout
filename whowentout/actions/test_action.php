<?php

class TestAction extends Action
{

    function execute()
    {
        $mapper = new RouteMapper();

        $mapper->add('event/:num');
    }

}
