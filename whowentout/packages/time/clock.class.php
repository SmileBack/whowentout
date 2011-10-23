<?php

class Clock
{

    private $delta = 0;
    private $timezone;

    function __construct(DateTimeZone $timezone)
    {
        $this->timezone = $timezone;
    }

    function set($time)
    {
        if (is_string($time))
            $time = new XDateTime($time, $this->timezone);

        $this->set_delta( $time->getTimestamp() - $this->actual_time()->getTimestamp() );
    }

    function get()
    {
        $time = $this->actual_time();
        $time->modify("+{$this->delta} seconds");
        return $time;
    }

    function set_delta($seconds)
    {
        $this->delta = $seconds;
    }

    private function actual_time()
    {
        return new XDateTime('now', $this->timezone);
    }
    
}
