<?php

class SmilePermission
{
    
    function check(XUser $sender, XUser $receiver, XParty $party)
    {
        $smile_engine = new SmileEngine();
        $checkin_engine = new CheckinEngine();

        if ( $smile_engine->get_num_smiles_left_to_give($sender, $party) == 0 ) {
            return FALSE;
        }

        if ( $smile_engine->smile_was_sent($sender, $receiver, $party) )
            return FALSE;

        if ( $sender->gender == $receiver->gender )
            return FALSE;

        if ( ! $checkin_engine->user_has_checked_into_party($sender, $party) )
            return FALSE;

        if ( ! $checkin_engine->user_has_checked_into_party($receiver, $party) )
            return FALSE;

        return TRUE;
    }

}
