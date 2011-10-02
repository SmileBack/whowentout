<?php

class HelpNotificationsPlugin extends CI_Plugin
{

    private $ci;

    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->library('flag');
    }

    /**
     * Occurs when a $e->user checks into a $e->party.
     * @param XUser $e->user
     * @param XParty $e->party
     */
    function on_checkin($e)
    {
    }

}
