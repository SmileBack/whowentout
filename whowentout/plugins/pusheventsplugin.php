<?php

class PushEventsPlugin
{
  
  function on_chat_sent($e) {
    serverinbox()->push("chat_{$e->sender->id}", $e->version);
  }
  
  function on_chat_received($e) {
    serverinbox()->push("chat_{$e->receiver->id}", $e->version);
  }
  
  /**
   * Occurs when a $e->user checks into a $e->party.
   * @param XUser $e->user
   * @param XParty $e->party
   */
  function on_checkin($e) {
    $e->party->increment_version();
  }
  
  function on_user_came_online($e) {
    foreach ($e->user->get_recently_attended_parties() as $party) {
      $party->increment_version();
    }
    $this->broadcast_user_came_online($e->user);
  }
  
  private function broadcast_user_came_online($source_user) {
    $ci =& get_instance();
    $ci->load->library('chat');
    
    $user_ids = $ci->chat->chatted_with_user_ids($source_user);
    foreach ($user_ids as $user_id) {
      $ci->chat->send($source_user, $user_id, 'online', 'notice');
    }
  }
  
  function on_user_went_offline($e) {
    foreach ($e->user->get_recently_attended_parties() as $party) {
      $party->increment_version();
    }
    $this->broadcast_user_went_offline($e->user);
  }
  
  private function broadcast_user_went_offline($source_user) {
    $ci =& get_instance();
    $ci->load->library('chat');
    
    $user_ids = $ci->chat->chatted_with_user_ids($source_user);
    foreach ($user_ids as $user_id) {
      $ci->chat->send($source_user, $user_id, 'offline', 'notice');
    }
  }
  
}
