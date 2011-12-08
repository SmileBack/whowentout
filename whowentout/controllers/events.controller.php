<?php

class Events_Controller extends Controller
{

    function test()
    {
        /* @var $installer PackageInstaller */
        // $installer = factory()->build('package_installer');
    }

    function index($date = null)
    {
        if ($date == null)
            $date = app()->clock()->today();
        else
            $date = DateTime::createFromFormat($date, 'Ymd');

        print r::page(array(
                           'content' => r::events_view(array(
                                                            'date' => $date,
                                                       )),
                      ));
    }

    private function default_date()
    {
        return app()->clock()->today();
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
