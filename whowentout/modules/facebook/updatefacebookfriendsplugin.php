<?php

class UpdateFacebookFriendsPlugin extends Plugin
{

    private $enabled = TRUE;

    function on_after_controller_request($e)
    {
        if ($this->enabled && logged_in() && current_user()->friends_need_update() ) {
            $this->update_facebook_friends_for_user( current_user() );
        }
    }

    function update_facebook_friends_for_user(XUser $user)
    {
        if ($user->has_facebook_permission('offline_access')) {
            job_call_async('update_facebook_friends', current_user()->id);
        }
        else {
            update_facebook_friends(current_user()->id);
        }
    }

}
