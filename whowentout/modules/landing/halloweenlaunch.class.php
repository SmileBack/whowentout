<?php

class HalloweenLaunch
{

    /**
     * @var Clock
     */
    private $clock;

    function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    /**
     * @return XDateTime
     */
    function get_launch_date()
    {
        $ci =& get_instance();
        $ci->config->load('launch');
        $timezone = new DateTimeZone($ci->config->item('launch_timezone'));
        return new XDateTime($ci->config->item('launch_date'), $timezone);
    }

    function has_launched()
    {
        return $this->clock->get_time() >= $this->get_launch_date();
    }

}
