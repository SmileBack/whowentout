<?php

class UpdateFacebookFriendsPlugin extends CI_Plugin
{
  
  function on_page_load($e) {
    if (logged_in()) {
      job_call_async('update_facebook_friends', current_user()->id);
    }
  }
  
}
