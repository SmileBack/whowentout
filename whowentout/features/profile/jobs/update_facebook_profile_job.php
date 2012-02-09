<?php

class UpdateFacebookProfileJob extends Job
{
    function run()
    {
        /* @var $profile_updater FacebookProfileUpdater */
        $profile_updater = build('facebook_profile_updater');
        $profile_updater->update_profile($this->options['user_id']);
    }
}
