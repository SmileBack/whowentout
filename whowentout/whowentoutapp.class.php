<?php

class WhoWentOutApp extends FireApp
{

    /**
     * @var \Database
     */
    protected $database;

    /**
     * @var Clock
     */
    protected $clock;

    function __construct(ClassLoader $class_loader, Database $database, Clock $clock)
    {
        parent::__construct($class_loader, $database);
        
        $this->database = $database;
        $this->clock = $clock;
    }

    /**
     * @return Clock
     */
    function clock()
    {
        return $this->clock;
    }

    function event_link($event)
    {
        return 'day/' . $event->date->format('Ymd');
    }

    function goto_event($event, $fragment = '')
    {
        redirect($this->event_link($event) . $fragment);
    }

    function event_invite_link($event)
    {
        return "events/$event->id/invite";
    }
    
}
