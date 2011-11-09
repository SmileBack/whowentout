<?php

class ViewPartyPermission
{
    
    function check(XUser $user, XParty $party)
    {
        $checkin_engine = new CheckinEngine();

        if ($checkin_engine->user_has_checked_into_party($user, $party))
            return TRUE;
        else
            return FALSE;
    }

}
