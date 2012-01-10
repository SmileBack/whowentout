<?php

class Checkins_Controller extends Controller
{

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    /* @var $invite_engine InviteEngine */
    private $invite_engine;

    function __construct()
    {
        $this->checkin_engine = factory()->build('checkin_engine');
        $this->invite_engine = factory()->build('invite_engine');
    }

    function create()
    {
        $current_user = auth()->current_user();

        $event_id = isset($_SESSION['checkins_create_event_id'])
                    ? $_SESSION['checkins_create_event_id']
                    : $_POST['event_id'];

        $event = db()->table('events')->row($event_id);

        if (!$event)
            show_404();

        if ($current_user) {
            /* @var $checkin_engine CheckinEngine */
            $this->checkin_engine->checkin_user_to_event($current_user, $event);
            unset($_SESSION['checkins_create_event_id']);

            flash::message("You checked into " . $event->name . '.');

            if ($event->deal)
                app()->goto_event($event, "#deal/$event->id");
            elseif ($this->invite_engine->has_sent_invites($event, $current_user))
                app()->goto_event($event); // skip invite dialog
            else
                app()->goto_event($event, "#invite/$event->id"); // show invite dialog
        }
        else {
            $_SESSION['checkins_create_event_id'] = $event->id;
            redirect('login');
        }
        
    }

}
