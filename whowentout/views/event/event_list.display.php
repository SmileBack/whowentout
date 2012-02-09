<?php

class Event_List_Display extends Display
{

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    function process()
    {
        $this->checkin_engine = build('checkin_engine');

        $events = $this->checkin_engine->get_events_on_date($this->date);
        usort($events, array($this, 'compare_events'));
        $this->events = $events;
    }

    function compare_events($event_a, $event_b)
    {
        return $this->event_sort_value($event_b) - $this->event_sort_value($event_a);
    }

    function event_sort_value($event)
    {
        $value = 0;

        $value += (99 - $event->priority) << 0;

        $value += $this->checkin_engine->get_checkin_count($event) << 2;

        if ($event == $this->selected_event)
            $value += 1 << 19;

        return $value;
    }

}
