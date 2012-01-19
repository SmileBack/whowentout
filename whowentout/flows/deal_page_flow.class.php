<?php

class DealPageFlow extends PageFlow
{
    public $event_id;

    function __construct($event_id)
    {
        $this->event_id = $event_id;
    }

    public function current()
    {
        return $this->current_state;
    }

    public function set_state($state)
    {
        $this->current_state = $state;
    }

    public function get_next()
    {
        $state = $this->current();

        if ($state == DealPageFlow::START)
            return DealPageFlow::END;
        else
            return null;
    }

    protected function execute_end()
    {
        $event = $this->get_event();
        app()->goto_event($event);
    }

    private function get_event()
    {
        $event = db()->table('events')->row($this->event_id);
        return $event;
    }

}
