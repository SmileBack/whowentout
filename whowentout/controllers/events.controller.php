<?php

class Events_Controller extends Controller
{

    function __construct()
    {
    }

    function test()
    {
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

        if (isset($_SESSION['checkin_event_id'])) {
            $event_id = $_SESSION['checkin_event_id'];
            js()->whowentout->showDealDialog($event_id);
            unset($_SESSION['checkin_event_id']);
        }

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

    function deal()
    {
        print r::deal_popup(array(
            'user' => auth()->current_user(),
        ));
    }

    function deal_confirm()
    {
        $cell_phone_number = $_POST['user']['cell_phone_number'];
        auth()->current_user()->cell_phone_number = $this->format_phone_number($cell_phone_number);
        auth()->current_user()->save();

        redirect('events');
    }

    private function format_phone_number($phone_number)
    {
        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);

        $num_digits = strlen($phone_number);
        if ($num_digits == 7)
            $phone_number = preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $phone_number);
        elseif ($num_digits == 10)
            $phone_number = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '($1) $2-$3', $phone_number);

        return $phone_number;
    }

}
