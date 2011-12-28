<?php

class UpdateFacebookFriendsJob extends Job
{

    function run()
    {
        $user_id = $this->options['user_id'];
        $user = db()->table('users')->row($user_id);

        /* @var $updater FacebookFriendsUpdater */
        $updater = factory()->build('facebook_friends_updater');
        $updater->update_facebook_friends($user);
    }

}