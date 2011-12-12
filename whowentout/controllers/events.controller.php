<?php

class Events_Controller extends Controller
{

    function test()
    {
        $events_table = db()->table('events');
        $checkins_table = db()->table('checkins');


        print '<pre>';
        $query = $checkins_table
                ->where('event.place.name', 'nyc')
                ->order_by('event.name', 'asc')
                ->limit(3);
        print '<pre>';
        print $query->to_sql();
        print '</pre>';

        krumo::dump($query->parameters());
    }

    function inner_join($base_table_name, $base_column_name, $fk_table_name, $fk_column_name)
    {
        $sql = "INNER JOIN $fk_table_name ON $base_table_name.$base_column_name = $fk_table_name.$fk_column_name";
        return $sql;
    }

    function test_fb()
    {
        /* @var $auth FacebookAuth */
        $auth = factory()->build('auth');

        if ($auth->logged_in()) {
            $user = $auth->current_user();
            print a('events/logout', "logout (logged in as $user)");
        }
        else {
            print sprintf('<a href="%s">login</a>', $auth->get_login_url());
        }
    }

    function index($date = null)
    {
        if ($date == null)
            $date = app()->clock()->today();
        else {
            $date = DateTime::createFromFormat('Ymd', $date);
            $date->setTime(0, 0, 0);
        }

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
