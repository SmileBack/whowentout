<?php

class TestAction extends Action
{

    function execute()
    {
        $eid = 11;
        $event = to::event($eid);
        print $event->name;
    }

}
