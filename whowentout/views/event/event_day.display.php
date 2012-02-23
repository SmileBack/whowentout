<?php

class Event_Day_Display extends Display
{

    function process()
    {
        if (!auth()->logged_in())
            show_404();

        $this->checkin = $this->checkin_engine->get_checkin_on_date($this->current_user, $this->date);
        $this->checkin_event = $this->checkin ? $this->checkin->event : null;
    }

    private function prefetch_users()
    {
        $users = db()->table('checkins')->where('event.date', app()->clock()->today())
                                        ->user;

        db()->table('users')->prefetch($users->to_sql(), $users->parameters());
    }

}
