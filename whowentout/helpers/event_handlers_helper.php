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
  $body = "You and $receiver->full_name have smiled at each other. "
        . anchor(site_url("party/$party->id"), 'Click here') . " to go to the party.";
  job_call_async('send_email', $sender->id, $subject, $body);
  
  // Send email to the receiver
  $subject = "You and $sender->full_name have smiled at each other.";
  $body = "You and $sender->full_name have smiled at each other. "
        . anchor(site_url("party/$party->id"), 'Click here') . " to go to the party.";
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
  $subject = "Someone from {$party->place->name} smiled at you.";
  $body = "Someone from {$party->place->name} has smiled at you. "
        . anchor(site_url("party/$party->id"), 'Click here') . " to go to the party.";
  job_call_async('send_email', $receiver->id, $subject, $body);
}
