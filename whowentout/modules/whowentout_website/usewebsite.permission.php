<?php

class UseWebsitePermission
{

    function check(XUser $user)
    {
        $valid_genders = array('M', 'F');
        if ( ! in_array($user->gender, $valid_genders) )
            return FALSE;

        if (empty($user->hometown_city) || empty($user->hometown_state))
            return FALSE;
        
        if ($user->never_edited_profile())
            return FALSE;

        if ($user->grad_year == NULL)
            return FALSE;

        return TRUE;
    }

}
