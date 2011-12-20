<?php


class Events_Controller extends Controller
{

    function __construct()
    {
    }

    function test()
    {
        db()->table('invites')->where('user_id', 5);
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

    function invite($event_id)
    {
        $event = db()->table('events')->row($event_id);

        print r::page(array(
                           'content' => r::event_invite(array(
                               'event' => $event,
                           )),
                      ));
    }

}
