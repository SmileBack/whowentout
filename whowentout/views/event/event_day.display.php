<?php

class Event_Day_Display extends Display
{

    function process()
    {
        if (!auth()->logged_in())
            show_404();

        $this->checkin_engine = build('checkin_engine');

        $this->checkin = $this->checkin_engine->get_checkin_on_date($this->current_user, $this->date);
        $this->checkin_event = $this->checkin ? $this->checkin->event : null;

        $events = $this->checkin_engine->get_events_on_date($this->date);

        usort($events, $this->sort_events_comparision());

        $data = array(
          'template' => 'event-picker',
          'date' => to::json($this->date),
          'events' => to::json($events),
          'selected_event' => to::json($this->checkin_event),
        );

        $this->data = $data;
    }

    function sort_events_comparision()
    {
        return array($this, 'compare_events');
    }

    function compare_events($event_a, $event_b)
    {
        return $this->event_sort_value($event_b) - $this->event_sort_value($event_a);
    }

    function event_sort_value($event)
    {
        $value = 0;

        $value += (99 - $event->priority) << 0;
        $value += $event->count << 2;

        return $value;
    }

}
