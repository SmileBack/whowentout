<?php

class Checkins_Controller extends Controller
{

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
            $checkin_engine = factory()->build('checkin_engine');
            $checkin_engine->checkin_user_to_event($current_user, $event);
            unset($_SESSION['checkins_create_event_id']);

            //show deal dialog and notice
            js()->whowentout->showDealDialog($event->id);
            flash::message("You checked into " . $event->name . '.');

            app()->goto_event($event);
        }
        else {
            $_SESSION['checkins_create_event_id'] = $event->id;
            redirect('login');
        }
        
    }

}
