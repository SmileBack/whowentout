<?php

class CheckinPermission
{

    function check(XUser $user, XParty $party)
    {
        $checkinEngine = new CheckinEngine();
        
        if ( ! $party->college->get_door()->is_open() )
            return FALSE;

        if ( $checkinEngine->user_has_checked_into_party($user, $party))
            return FALSE;

        return TRUE;
    }

}
