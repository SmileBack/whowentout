<?php

class HelpNotificationsPlugin
{

    private $ci;

    function __construct()
    {
        $this->ci =& get_instance();
        $this->db = $this->ci->db;
    }

    /**
     * Occurs when a $e->user checks into a $e->party.
     * @param XUser $e->user
     * @param XParty $e->party
     */
    function on_checkin($e)
    {
        
        if ($this->is_first_checkin($e->user)) {
            
        }
    }

    function is_first_checkin(XUser $user)
    {
        $count = $this->db->from('party_attendees')
                ->where('user_id', $user->id)
                ->count_all_results();
        return $count == 1;
    }
    
}
