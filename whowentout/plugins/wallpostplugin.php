<?php

class WallPostPlugin
{
  
  /**
   * Occurs when $e->user checks into a $party.
   * 
   * @param XUser $e->user
   * @param XParty $e->party 
   */
  function on_checkin($e) {
  //  $message = "$user->full_name checked into {$party->place->name} using WhoWentOut.";
  //  job_call_async('post_to_wall', $user->id, $message, fb()->getAccessToken());
  }
  
}
