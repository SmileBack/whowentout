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
                                                  'checkin_time' => college()->get_time()->format('Y-m-d H:i:s'),
                                             ));

        $this->trigger('checkin', array(
                                       'user' => $user,
                                       'party' => $party,
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
    function get_checkin_for_date(XUser $user, XDateTime $date)
    {
        $query = $this->db->select('parties.id AS id')
                ->from('party_attendees')
                ->join('parties', 'party_attendees.party_id = parties.id')
                ->where('user_id', $user->id)
                ->where('date', $date->format('Y-m-d'));
        $parties = XObject::load_objects('XParty', $query);
        return empty($parties) ? NULL : $parties[0];
    }

    /**
     * @param  $date
     *   The date that the parties occured, NOT the date of checkin.
     * @return bool
     */
    function user_has_checked_in_on_date(XUser $user, XDateTime $date)
    {
        return $this->db->from('party_attendees')
                       ->join('parties', 'party_attendees.party_id = parties.id')
                       ->where('user_id', $user->id)
                       ->where('date', $date->format('Y-m-d'))
                       ->count_all_results() > 0;
    }

    function get_checkins_for_party($party)
    {
        $query = $this->db->select('user_id AS id')
                ->from('party_attendees')
                ->where('party_id', $party->id);

        return XUser::load_objects('XUser', $query);
    }

    function get_recently_attended_parties_for_user($user)
    {
        $now = $user->college->get_time();
        $cutoff = $now->getDay(-60);
        $rows = $this->db
                ->select('party_id AS id')
                ->from('party_attendees')
                ->join('parties', 'party_attendees.party_id = parties.id')
                ->order_by('date', 'desc')
                ->where('user_id', $user->id)
                ->where('date >', $cutoff->format('Y-m-d'));
        return XObject::load_objects('XParty', $rows);
    }

    function get_parties_availiable_for_checkin(XDateTime $time)
    {
        throw new Exception('Not yet implemented');
    }

    function get_num_checkins_for_user($user)
    {
        return $this->db->from('party_attendees')
                ->where('user_id', $user->id)
                ->count_all_results();
    }

    private function trigger($event_name, $event_data)
    {
        f()->trigger($event_name, $event_data);
    }

    private function current_time()
    {
        return college()->get_time();
    }

    private function init_db()
    {
        $ci =& get_instance();
        $this->db = $ci->db;
    }

}
