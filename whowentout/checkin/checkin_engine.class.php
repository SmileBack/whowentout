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

    /**
     * @var DatabaseTable
     */
    private $checkins;

    function __construct(Database $database, Clock $clock)
    {
        $this->database = $database;
        $this->clock = $clock;

        $this->checkins = $this->database->table('checkins');
    }

    private $event_checkins = array();
    private function load_event_cache_if_missing($event)
    {
        if (!isset($this->event_checkins[$event->id]))
            $this->load_event_cache($event);
    }
    private function load_event_cache($event)
    {
        $this->event_checkins[$event->id] = $this->checkins->where('event_id', $event->id)->to_array();
    }
    private function clear_event_cache($event)
    {
        unset($this->event_checkins[$event->id]);
    }

    function checkin_user_to_event($user, $event)
    {
        $previous_checkin = $this->get_checkin_on_date($user, $event->date);

        if ($previous_checkin && $previous_checkin->event == $event)
            return;
        
        if ($previous_checkin) {
            $this->remove_checkin_on_date($user, $event->date);
        }
        
        $this->checkins->create_row(array(
                                         'time' => $this->clock->get_time(),
                                         'user_id' => $user->id,
                                         'event_id' => $event->id,
                                    ));

        $this->clear_event_cache($event);
    }

    function user_has_checked_into_event($user, $event)
    {
        $this->load_event_cache_if_missing($event);
        foreach ($this->event_checkins[$event->id] as $checkin)
            if ($checkin->user_id == $user->id)
                return true;

        return false;
    }

    function get_checkin_on_date($user, DateTime $date)
    {
        if ($user == null)
            return null;
        
        return $this->checkins->where('user_id', $user->id)
                              ->where('event.date', $date)
                              ->first();
    }

    function remove_checkin_on_date($user, DateTime $date)
    {
        $checkin = $this->get_checkin_on_date($user, $date);
        if ($checkin)
            $this->checkins->destroy_row($checkin->id);

        $this->clear_event_cache($checkin->event);
    }

    function get_checkins_for_event($event)
    {
        return $event->checkins->order_by('time', 'desc')->to_array();
    }

    function get_checkins_for_user($user)
    {
        return $user->checkins->order_by('event.date', 'desc')->to_array();
    }
    
}
