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

}

class PartyGroupPhase
{
    const EarlyCheckin = 'EarlyCheckin';
    const Checkin = 'Checkin';
    const CheckinsClosed = 'CheckinsClosed';
}
