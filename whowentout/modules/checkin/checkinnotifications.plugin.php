<?php

class CheckinNotificationsPlugin extends Plugin
{

    private $enabled = FALSE;

    /**
     * Occurs when a $e->user checks into a $e->party.
     * @param XUser $e->user
     * @param XParty $e->party
     */
    function on_checkin($e)
    {
        $this->ci =& get_instance();
        if ($this->enabled) {
            $message = "You checked into {$e->party->place->name}.";
            $this->ci->notification->send($e->user, $message);
        }
    }

}
