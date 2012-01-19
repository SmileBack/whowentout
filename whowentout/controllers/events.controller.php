<?php

class Events_Controller extends Controller
{

    /* @var $db Database */
    private $db;

    /* @var $auth FacebookAuth */
    private $auth;

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    /* @var $invite_engine InviteEngine */
    private $invite_engine;

    function __construct()
    {
        $this->db = db();
        $this->auth = build('auth');
        $this->checkin_engine = build('checkin_engine');
        $this->invite_engine = build('invite_engine');
    }

    function test()
    {
        /* @var $flow PageFlow */
        $flow = $_SESSION['flow'];
        //        $flow->event_id = 2;
        $flow->set_state(CheckinPageFlow::CHECKIN);

        PageFlow::transition();
    }

    function index($date = null)
    {
        $current_user = $this->auth->current_user();

        if (isset($_SESSION['checkins_create_event_id']))
            redirect('checkins/create');

        if ($date == null) {
            $date = app()->clock()->today();
            redirect('events/index/' . $date->format('Ymd'));
        }
        else {
            $date = DateTime::createFromFormat('Ymd', $date);
            $date = new XDateTime($date->format('Y-m-d'));
            $date->setTime(0, 0, 0);
        }

        print r::page(array(
            'content' => r::events_date_selector(array('selected_date' => $date))
                    . r::event_day(array(
                        'checkin_engine' => $this->checkin_engine,
                        'current_user' => $current_user,
                        'date' => $date,
                    )),
        ));
    }

    function index_ajax($date)
    {
        $date = DateTime::createFromFormat('Ymd', $date);
        $date = new XDateTime($date->format('Y-m-d'));
        $date->setTime(0, 0, 0);

        print r::event_day(array(
            'checkin_engine' => $this->checkin_engine,
            'current_user' => auth()->current_user(),
            'date' => $date,
        ));
    }


    private function default_date()
    {
        return app()->clock()->today();
    }

    function invite($event_id)
    {
        $event = $this->db->table('events')->row($event_id);

        PageFlow::start(new InvitePageFlow($event->id));

        print r::event_invite(array(
            'event' => $event,
        ));
    }

    function deal($event_id)
    {
        $event = $this->db->table('events')->row($event_id);
        $current_user = $this->auth->current_user();
        $has_invited = $this->invite_engine->has_sent_invites($event, $current_user);

        if (isset($_GET['show']) && $_GET['show'] == 'true') {
            PageFlow::start(new DealPageFlow($event->id));
        }

        print r::deal_popup(array(
            'user' => $current_user,
            'event' => $event,
            'has_invited' => $has_invited,
        ));
    }

    function deal_confirm()
    {
        $cell_phone_number = $_POST['user']['cell_phone_number'];

        $event_id = $_POST['event_id'];
        $event = $this->db->table('events')->row($event_id);
        $current_user = $this->auth->current_user();

        $current_user->cell_phone_number = $this->format_phone_number($cell_phone_number);
        $current_user->save();

        PageFlow::transition();
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
