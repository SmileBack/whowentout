<?php

class Event_List_Display extends Display
{
    function process()
    {
        /* @var $checkin_engine CheckinEngine */
        $checkin_engine = build('checkin_engine');

        $events = $checkin_engine->get_events_on_date($this->date);
        usort($events, function($event_a, $event_b) use($checkin_engine) {
            $event_a_count = $checkin_engine->get_checkin_count($event_a);
            $event_b_count = $checkin_engine->get_checkin_count($event_b);
            return $event_b_count - $event_a_count;
        });

        $this->events = $events;
    }
}
