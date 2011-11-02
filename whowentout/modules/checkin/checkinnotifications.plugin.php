<?php

class CheckinNotificationsPlugin extends Plugin
{

    private $ci;

    private $enabled = FALSE;

    function __construct()
    {
        $this->ci =& get_instance();
    }

    /**
     * Occurs when a $e->user checks into a $e->party.
     * @param XUser $e->user
     * @param XParty $e->party
     */
    function on_checkin($e)
    {
        if ($this->enabled) {
            $message = "You checked into {$e->party->place->name}.";
            $this->ci->notification->send($e->user, $message);
        }
    }

}
