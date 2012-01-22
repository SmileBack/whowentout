<?php

class CheckinFlow extends Flow
{
    public $name = 'checkin';
    public $event_id = null;

    public function __construct($event_id)
    {
        $this->event_id = $event_id;
    }

}
