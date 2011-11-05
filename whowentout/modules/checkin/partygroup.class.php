<?php

class PartyGroup
{

    /**
     * @var Clock
     */
    private $clock;

    /**
     * @var XDateTime
     */
    private $date;

    /**
     * @var CheckinEngine
     */
    private $checkin_engine;

    function __construct(Clock $clock, XDateTime $date)
    {
        $this->clock = $clock;
        $this->date = $date->getDay(0);
        $this->checkin_engine = new CheckinEngine();
    }

    function get_date()
    {
        return $this->date;
    }

    function get_phase()
    {
        $current_time = $this->clock->get_time();

        if ($current_time >= $this->get_checkin_phase_start() && $current_time < $this->get_checkin_phase_end())
            return PartyGroupPhase::Checkin;
        elseif ($current_time >= $this->get_checkin_phase_end())
            return PartyGroupPhase::CheckinsClosed;
        else
            return PartyGroupPhase::EarlyCheckin;
    }

    function get_user_phase(XUser $user)
    {
        $phase = $this->get_phase();
        $selected_party = $this->get_selected_party($user);
        if (!$selected_party && $phase != PartyGroupPhase::CheckinsClosed)
            return PartyGroupPhase::Checkin;
        elseif ($selected_party && $phase == PartyGroupPhase::EarlyCheckin)
            return PartyGroupPhase::Attending;
        elseif ($selected_party && $phase == PartyGroupPhase::Checkin)
            return PartyGroupPhase::Attended;

        elseif ($selected_party && $phase == PartyGroupPhase::CheckinsClosed)
            return PartyGroupPhase::Attended;
        elseif (!$selected_party && $phase == PartyGroupPhase::CheckinsClosed)
            return PartyGroupPhase::CheckinsClosed;
    }

    function get_checkin_phase_start()
    {
        return $this->date->getDay(+1);
    }

    function get_checkin_phase_end()
    {
        return $this->date->getDay(+2);
    }

    function get_selected_party(XUser $user)
    {
        return $this->checkin_engine->get_checkin_for_date($user, $this->date);
    }

    /**
     * @return array (of XParty)
     */
    function get_parties()
    {
        $ci =& get_instance();
        $query = $ci->db->select('parties.id AS id')
                ->from('parties')
                ->where('date', $this->date->format('Y-m-d'));
        return XObject::load_objects('XParty', $query);
    }

    function has_parties()
    {
        $parties = $this->get_parties();
        return !empty($parties);
    }

}

class PartyGroupPhase
{
    const EarlyCheckin = 'EarlyCheckin';
    const Checkin = 'Checkin';
    const CheckinsClosed = 'CheckinsClosed';

    const Attending = 'Attending';
    const Attended = 'Attended';
}
