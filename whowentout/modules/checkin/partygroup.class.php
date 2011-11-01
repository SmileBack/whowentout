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

    function __construct(Clock $clock, XDateTime $date)
    {
        $this->clock = $clock;
        $this->date = $date;
    }

    function get_phase()
    {
        
    }

    function select_party(XParty $party)
    {
        
    }

    /**
     * @return XParty
     */
    function get_selected_party()
    {
        $ci = & get_instance();
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
    const EarlyCheckin = 1;
    const Checkin = 2;
    const CheckinsClosed = 3;
}
