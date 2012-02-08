<?php

class TestAction extends Action
{

    function execute()
    {
//        krumo::dump('START');
//
//        $event_one = db()->table('events')->row(27);
//        $event_two = db()->table('events')->row(27);
//
//        krumo::dump('END');
        $gateway = new DatabaseTableGateway(db(), 'events', 'id');
        $row = $gateway->get(27);
        $row = $gateway->get(27);
        $e = $gateway->get(2342342);
        $e = $gateway->get(2342342);
        krumo::dump($row);
    }

}
