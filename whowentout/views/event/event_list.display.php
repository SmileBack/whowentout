<?php

class Event_List_Display extends Display
{

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    protected $defaults = array(
        'type' => 'all',
        'add_form' => true,
        'notice' => false,
        'explanation' => '',
    );

    function process()
    {
        $this->checkin_engine = build('checkin_engine');

        $events = $this->checkin_engine->get_events_on_date($this->date);

        $events = array_filter($events, $this->matches_type_filter());
        usort($events, $this->sort_events_comparision());

        $data = array(
          'template' => 'event-list',
          'date' => to::json($this->date),
          'events' => to::json($events),
          'selected_event' => to::json($this->selected_event),
          'explanation' => $this->explanation,
        );

        $this->data = $data;
    }

    function matches_type_filter()
    {
        $type_pattern = $this->type;
        if (is_array($type_pattern))
            $type_pattern = implode('|', $type_pattern);
        $callback = function($event) use($type_pattern) {
            $type = "{$event->place->type}, all";
            return preg_match('/\b(' . $type_pattern . ')\b/', $type) == 1;
        };
        return $callback;
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
