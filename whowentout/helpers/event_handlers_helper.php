<?php

/**
 * Occurs when a $sender smiles at $receiver.
 * @param XUser $sender
 * @param XUser $receiver
 * @param XParty $party 
 */
function on_smile($sender, $receiver, $party) {
  if ( $sender->was_smiled_at($receiver, $party) ) {
    raise_event('smile_back', $sender, $receiver, $party);
  }
  else {
    raise_event('smile_at', $sender, $receiver, $party);
  }
}

/**
 * Occurs when $sender smiles *back* at $receiver.
 * 
 * @param XUser $sender
 * @param XUser $receiver
 * @param XParty $party 
 */
function on_smile_back($sender, $receiver, $party) {
  // Send email to the sender 
  $subject = "You and $receiver->full_name have smiled at each other.";
  $body = ci()->load->view('emails/match_notification_view', array(
    'sender' => $sender,
    'receiver' => $receiver,
    'party' => $party,
  ), TRUE);
  job_call_async('send_email', $sender->id, $subject, $body);
  
  // Send email to the receiver
  $subject = "You and $sender->full_name have smiled at each other.";
  $body = ci()->load->view('emails/match_notification_view', array(
    'sender' => $receiver,
    'receiver' => $sender,
    'party' => $party,
  ), TRUE);
  job_call_async('send_email', $receiver->id, $subject, $body);
}

/**
 * Occurs when $sender smiles at $receiver but no match has (yet) occured.
 * 
 * @param XUser $sender
 * @param XUser $receiver
 * @param XParty $party
 */
function on_smile_at($sender, $receiver, $party) {
  $subject = "A $sender->gender_word from {$party->place->name} has smiled at you.";
  $body = ci()->load->view('emails/smile_received_email', array(
    'sender' => $sender,
    'receiver' => $receiver,
    'party' => $party,
    'date' => current_time(TRUE),
  ), TRUE);
  job_call_async('send_email', $receiver->id, $subject, $body);
}

function on_page_load($uri) {
  if (logged_in()) {
    job_call_async('update_facebook_friends', current_user()->id);
  }
}

/**
 * Occurs when $user checks into a $party.
 * 
 * @param XUser $user
 * @param XParty $party 
 */
function on_checkin($user, $party) {
//  $message = "$user->full_name checked into {$party->place->name} using WhoWentOut.";
//  job_call_async('post_to_wall', $user->id, $message, fb()->getAccessToken());
}
