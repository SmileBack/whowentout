<?php

class CheckinPermission
{

    function check(XUser $user, XParty $party)
    {
        $checkin_engine = new CheckinEngine();
        
        if ( $checkin_engine->user_has_checked_into_party($user, $party) )
            return FALSE;

        $party_group = new PartyGroup(college()->get_clock(), $party->date);
        $party_group_phase = $party_group->get_phase();

        if ($party_group_phase == PartyGroupPhase::CheckinsClosed)
            return FALSE;

        if ($party_group_phase == PartyGroupPhase::Checkin
            && $checkin_engine->user_has_checked_in_on_date($user, $party->date)) {
            return FALSE;
        }

        return TRUE;
    }

}
