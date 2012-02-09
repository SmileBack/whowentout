<?php

class TestAction extends Action
{

    function execute()
    {
        $checkins = db()->table('checkins')->where('event.date', app()->clock()->today());
        print r::admin_deals(array(
            'checkins' => $checkins,
        ));
    }

}
