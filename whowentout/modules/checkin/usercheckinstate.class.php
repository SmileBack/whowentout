<?php

class UserCheckinState
{

    /**
     * @var XUser
     */
    private $user;

    /**
     * @var CheckinEngine
     */
    private $checkin_engine;

    function __construct(XUser $user)
    {
        $this->user = $user;
        $this->checkin_engine = new CheckinEngine();
    }

    /**
     * @return XUser
     */
    function get_user()
    {
        return $this->user;
    }

    /**
     * @return XCollege
     */
    function get_college()
    {
        return $this->user->college;
    }

    /**
     * @return XDateTime
     */
    function get_time()
    {
        return $this->get_college()->get_time();
    }

    function get_today()
    {
        return $this->get_time()->getDay(0);
    }

    function get_party_day()
    {
        return $this->get_today()->getDay(-1);
    }

    function user_has_checked_in()
    {
        return $this->checkin_engine->user_has_checked_in_on_date($this->get_user(), $this->get_party_day());
    }

    function get_open_parties()
    {
        return $this->get_college()->open_parties( $this->get_time() );
    }

    function get_checked_in_party()
    {
        return $this->checkin_engine->get_checkin_for_date($this->get_user(), $this->get_party_day());
    }

}
