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
    }

    function user_has_checked_into_event($user, $event)
    {
        return $this->checkins->where('user_id', $user->id)
                       ->where('event_id', $event->id)
                       ->count() > 0;
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
    }

    function get_checkins_for_event($event)
    {
        return $this->checkins->where('event_id', $event->id)->to_array();
    }
    
}
