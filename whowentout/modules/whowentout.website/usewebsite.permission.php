<?php

class UseWebsitePermission
{

    private $reasons = array();

    const GENDER_MISSING = 'GENDER_MISSING';
    const HOMETOWN_MISSING = 'HOMETOWN_MISSING';
    const NEVER_EDITED_PROFILE = 'NEVER_EDITED_PROFILE';
    const GRAD_YEAR_MISSING = 'GRAD_YEAR_MISSING';
    const NETWORK_INFO_MISSING = 'NETWORK_INFO_MISSING';
    const MISSING_IMAGE = 'MISSING_IMAGE';

    function check(XUser $user)
    {
        $valid_genders = array('M', 'F');

        $this->reasons = array();
        
        if (!in_array($user->gender, $valid_genders))
            $this->add_reason(UseWebsitePermission::GENDER_MISSING);

        if (empty($user->hometown_city) || empty($user->hometown_state))
            $this->add_reason(UseWebsitePermission::HOMETOWN_MISSING);

        if ($user->never_edited_profile())
            $this->add_reason(UseWebsitePermission::NEVER_EDITED_PROFILE);

        if ($user->grad_year == NULL || $user->grad_year == 0)
            $this->add_reason(UseWebsitePermission::GRAD_YEAR_MISSING);

        if ($user->college != college())
            $this->add_reason(UseWebsitePermission::NETWORK_INFO_MISSING);

//        $profile_picture = new UserProfilePicture($user);
//        if ($profile_picture->is_missing()) {
//            $this->add_reason(UseWebsitePermission::MISSING_IMAGE);
//        }

        return empty($this->reasons);
    }

    function cant_because($reason)
    {
        return in_array($reason, $this->reasons);
    }

    function get_reasons()
    {
        return $this->reasons;
    }

    protected function add_reason($reason)
    {
        $this->reasons[] = $reason;
    }

}
