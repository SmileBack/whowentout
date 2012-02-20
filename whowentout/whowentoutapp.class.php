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

    function __construct(ClassLoader $class_loader, Database $database, EventDispatcher $event_dispatcher, Clock $clock)
    {
        parent::__construct($class_loader, $database, $event_dispatcher);
        
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

    function profile_link($user)
    {
        return 'profile/' . $user->id;
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
