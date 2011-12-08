<?php

class Events_Controller extends Controller
{

    function test()
    {
        $day = app()->clock()->get_time()->getDay(1);
        $today_events = db()->table('events')->where('date', $day);
        foreach ($today_events as $id => $event) {
            krumo::dump($event->name);
        }
    }
    
    function index()
    {
        print r::page(array(
                           'content' => r::events_view(array(
                                                           'date' => app()->clock()->today(),
                                                       )),
                      ));
    }

    function invite()
    {
        print r::page(array(
                           'content' => r::event_invite(),
                      ));
    }

    function test_session()
    {
        $session_handler = factory()->build('session_handler');
        session_set_save_handler(
            array($session_handler, 'open'),
            array($session_handler, 'close'),
            array($session_handler, 'read'),
            array($session_handler, 'write'),
            array($session_handler, 'destroy'),
            array($session_handler, 'gc')
        );

        session_start();
        $_SESSION['test'] = 52;
    }

}
