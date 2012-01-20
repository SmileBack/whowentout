<?php

class CheckinAction extends Action
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

    function execute()
    {
        $current_user = auth()->current_user();

        $event_id = $_POST['event_id'];

        $event = db()->table('events')->row($event_id);

        if (!$event)
            show_404();

        if (!$current_user)
            show_404();

        /* @var $checkin_engine CheckinEngine */
        $this->checkin_engine->checkin_user_to_event($current_user, $event);

        flash::message("You checked into " . $event->name . '.');
    }

}
