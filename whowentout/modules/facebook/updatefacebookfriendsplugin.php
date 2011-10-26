<?php

class UpdateFacebookFriendsPlugin extends Plugin
{

    function on_after_controller_request($e)
    {
        if ( logged_in() && current_user()->friends_need_update() ) {
            job_call_async('update_facebook_friends', current_user()->id);
        }
    }

}