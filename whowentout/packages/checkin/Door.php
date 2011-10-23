<?php

class Door
{

    private $clock;

    function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    function is_open()
    {
        return FALSE;
    }

    function doors_are_open()
    {
        return $this->get_closing_time() < $this->get_opening_time();
    }

    function get_opening_time()
    {
        $clock = $this->get_clock();
        $now = $clock->get();

        $today = $now->getDay(0);
        $opening_time_today = $this->get_opening_time_for_day($today);

        //time has passed already
        if (!$today->isCheckinDay() || $now->getTimestamp() >= $opening_time_today->getTimestamp()) {
            $next_checkin_day = $this->get_next_checkin_day($today);
            return $this->get_opening_time_for_day($next_checkin_day);
        }
        else {
            return $opening_time_today;
        }
    }

    function get_closing_time()
    {
        $clock = $this->get_clock();
        $now = $clock->get();

        $today = $now->getDay(0);
        $closing_time_today = $this->get_closing_time_for_day($today);

        if (!$today->isCheckinDay() || $now->getTimestamp() >= $closing_time_today->getTimestamp()) {
            $next_checkin_day = $this->get_next_checkin_day($today);
            return $this->get_closing_time_for_day($next_checkin_day);
        }
        else {
            return $closing_time_today;
        }
    }

    private function get_opening_time_for_day(XDateTime $day)
    {
        $opening_time = clone $day;
        $opening_time->setTime(2, 0, 0);
        return $opening_time;
    }

    private function get_closing_time_for_day(XDateTime $day)
    {
        $closing_time = clone $day;
        $closing_time->modify('+1 day');
        return $closing_time;
    }

    private function get_next_checkin_day(XDateTime $current_day)
    {
        $is_checkin_day = function($day)
        {
            return $day->isCheckinDay();
        };
        return $current_day->dayOfType($is_checkin_day, +1);
    }

    /**
     * @return Clock
     */
    function get_clock()
    {
        return $this->clock;
    }

}
