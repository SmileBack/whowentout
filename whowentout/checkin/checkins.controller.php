<?php

class Checkins_Controller extends Controller
{
    
    function create()
    {
        $current_user = auth()->current_user();
        $event_id = $_POST['event_id'];
        $event = db()->table('events')->row($event_id);
        
        if ($event && $current_user) {
            /* @var $checkin_engine CheckinEngine */
            $checkin_engine = factory()->build('checkin_engine');
            $checkin_engine->checkin_user_to_event($current_user, $event);
            redirect('events/index/' . $event->date->format('Ymd'));
        }
    }

}
