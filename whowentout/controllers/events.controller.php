<?php

class Events_Controller extends Controller
{

    function __construct()
    {
        app()->clock()->set_time('2011-12-08');
    }

    function test()
    {
        $user_id = 5;
        $users_table = db()->table('users');
        $checkins_table = db()->table('checkins');
        $events_table = db()->table('events');

        $result_set = new ResultSet($users_table);
        $result_set = $result_set->where('name', 'woo');

        print '<pre>';
        print $result_set->to_sql();
        print '</pre>';
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
        $current_user = auth()->current_user();

        if (isset($_SESSION['checkins_create_event_id']))
            redirect('checkins/create');

        if ($date == null)
            $date = app()->clock()->today();
        else {
            $date = DateTime::createFromFormat('Ymd', $date);
            $date->setTime(0, 0, 0);
        }

        /* @var $checkin_engine CheckinEngine */
        $checkin_engine = factory()->build('checkin_engine');

        $checkin = $checkin_engine->get_checkin_on_date($current_user, $date);
        $selected_event = $checkin ? $checkin->event : null;

        print r::page(array(
                           'content' => r::events_view(array(
                                                            'date' => $date,
                                                            'checkin' => $checkin,
                                                            'selected_event' => $selected_event,
                                                       )),
                      ));
    }

    /**
     * @return CheckinEngine
     */
    private function checkin_engine()
    {
        return factory()->build('checkin_engine');
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

}
