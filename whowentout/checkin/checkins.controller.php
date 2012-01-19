<?php

class Checkins_Controller extends Controller
{

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    /* @var $invite_engine InviteEngine */
    private $invite_engine;

    function __construct()
    {
        $this->checkin_engine = build('checkin_engine');
        $this->invite_engine = build('invite_engine');
    }

    function create()
    {
        $current_user = auth()->current_user();

        $event_id = $_POST['event_id'];

        $event = db()->table('events')->row($event_id);

        if (!$event)
            show_404();

        if (!$current_user)
            show_404();

        $flow = new CheckinPageFlow();

        /* @var $checkin_engine CheckinEngine */
        $this->checkin_engine->checkin_user_to_event($current_user, $event);

        flash::message("You checked into " . $event->name . '.');

        $flow->event_id = $event->id;
        $flow->has_sent_invite = $this->invite_engine->has_sent_invites($event, $current_user);

        PageFlow::start($flow);
        PageFlow::transition();
    }

}
