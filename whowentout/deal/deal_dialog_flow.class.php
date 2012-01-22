<?php

class DealDialogFlow extends Flow
{

    public $event_id;

    function __construct($event_id)
    {
        $this->event_id = $event_id;
    }

}
