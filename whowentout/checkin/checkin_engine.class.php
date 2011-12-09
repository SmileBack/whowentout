<?php

class CheckinEngine
{

    /**
     * @var \Database
     */
    private $database;

    /**
     * @var \Clock
     */
    private $clock;

    function __construct(Database $database, Clock $clock)
    {
        $this->database = $database;
        $this->clock = $clock;
    }

    function checkin_user_to_event($user, $event)
    {

    }

    function get_checkin_on_date($user, DateTime $date)
    {

    }

    function remove_checkin_on_date($user, DateTime $date)
    {
        
    }

    function get_checkins_for_event($event)
    {
        
    }

}
