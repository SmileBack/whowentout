<?php

class EmailNotificationsPlugin
{
  
  /**
   * Occurs when $e->sender smiles at $e->receiver but no match has (yet) occured.
   * If you want to attach behaviors to when a match occured, see on_smile_back.
   * 
   * @param XUser $e->sender
   * @param XUser $e->receiver
   * @param XParty $e->party
   */
  function on_smile_at($e) {
    $ci =& get_instance();
    
    $subject = "A $sender->gender_word from {$e->party->place->name} has smiled at you.";
    $body = $ci->load->view('emails/smile_received_email', array(
      'sender' => $e->sender,
      'receiver' => $e->receiver,
      'party' => $e->party,
      'date' => current_time(TRUE),
    ), TRUE);
    job_call_async('send_email', $e->receiver->id, $subject, $body);
  }
  
  /**
   * Occurs when $sender smiles *back* at $e->receiver.
   * 
   * @param XUser $e->sender
   * @param XUser $e->receiver
   * @param XParty $e->party 
   */
  function on_smile_back($e) {
    $ci =& get_instance();
    
    // Send email to the sender 
    $subject = "You and {$e->receiver->full_name} have smiled at each other.";
    $body = $ci->load->view('emails/match_notification_view', array(
      'sender' => $e->sender,
      'receiver' => $e->receiver,
      'party' => $e->party,
    ), TRUE);
    job_call_async('send_email', $e->sender->id, $subject, $body);
    
    // Send email to the receiver
    $subject = "You and {$e->receiver->full_name} have smiled at each other.";
    $body = $ci->load->view('emails/match_notification_view', array(
      'sender' => $e->receiver,
      'receiver' => $e->sender,
      'party' => $e->party,
    ), TRUE);
    job_call_async('send_email', $e->receiver->id, $subject, $body);
  }
  
}
