<?php

class Event_Links_Display extends Display
{

    function process()
    {
        $this->events = db()->table('events')->where('date', $this->date)
                                             ->order_by('count', 'desc')
                                             ->to_array();

        $this->events = array_filter($this->events, function($event) {
            return $event->count > 0;
        });
    }

}
