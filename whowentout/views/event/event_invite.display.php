<?php

class Event_Invite_Display extends Display
{
    function process()
    {
        /* @var $invite_engine InviteEngine */
        $this->invite_engine = build('invite_engine');

        /* @var $checkin_engine CheckinEngine */
        $this->checkin_engine = build('checkin_engine');

        $this->current_user = auth()->current_user();
        $this->friends = $this->current_user->friends->where('networks.name', 'Stanford')
                                                     ->order_by('first_name');
    }
}