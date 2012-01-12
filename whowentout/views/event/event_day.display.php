<?php

class Event_Day_Display extends Display
{

    function process()
    {
        $this->checkin = $this->checkin_engine->get_checkin_on_date($this->current_user, $this->date);
        $this->checkin_event = $this->checkin ? $this->checkin->event : null;
    }

}