<?php

class CheckinEngine
{

    private $db;

    function __construct()
    {
        $this->init_db();
    }

    function checkin_user_to_party($user, $party)
    {
        $this->db->insert('party_attendees', array(
                                                  'user_id' => $user->id,
                                                  'party_id' => $party->id,
                                                  'checkin_time' => $this->current_time()->format('Y-m-d H:i:s'),
                                             ));
    }

    function user_has_checked_into_party($user, $party)
    {
        return $this->db->from('party_attendees')
                        ->where('user_id', $user->id)
                        ->where('party_id', $party->id)
                        ->count_all_results() > 0;
    }

    /**
     * @param  $date
     *   The date that the parties occured, NOT the date of checkin.
     * @return bool
     */
    function user_has_checked_in_on_date($date)
    {
        
    }

    function get_checkins_for_party($party)
    {
        $query = $this->db->select('user_id AS id')
                          ->from('party_attendees')
                          ->where('party_id', $party->id);
        
        return XUser::load_objects('XUser', $query);
    }

    function get_checkins_for_user($user)
    {
        
    }

    function get_parties_open_for_checkin($time)
    {

    }

    private function current_time()
    {
        return current_time();
    }

    private function init_db()
    {
        $ci =& get_instance();
        $this->db = $ci->db;
    }

}