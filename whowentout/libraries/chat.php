<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Chat
{
  
  private $ci;
  private $version;
  
  function __construct() {
    $this->ci =& get_instance();
    $this->db = $this->ci->db;
  }
  
  function send($sender_id, $receiver_id, $message) {
    $sender = user($sender_id);
    $receiver = user($receiver_id);
    $this->db->insert('chat_messages', array(
      'sender_id' => $sender->id,
      'receiver_id' => $receiver->id,
      'message' => $message,
      'sent_at' => current_time()->getTimestamp(),
    ));
    
    $this->version = $this->db->insert_id();
    
    serverinbox()->push("chat_{$sender->id}", $this->version);
    serverinbox()->push("chat_{$receiver->id}", $this->version);
  }
  
  function messages($user_id, $version) {
    $user = user($user_id);
    $query = "SELECT * FROM chat_messages WHERE (sender_id = ? OR receiver_id = ?)
                AND id > ?
                ORDER BY id ASC";
    $version = intval($version);
    
    $messages = $this->db->query($query, array($user->id, $user->id, $version))->result();
    
    if ( ! empty($messages) ) {
      $last_message = $messages[ count($messages) - 1 ];
      $this->version = intval($last_message->id);
    }
    else {
      $this->version = $version;
    }
    
    return $messages;
  }
  
  function mark_as_read($by, $from) {
    $from = user($from);
    $by = user($by);
    
    $this->db->where('receiver_id', $by->id)
             ->where('sender_id', $from->id)
             ->update('chat_messages', array('is_read' => 1));
  }
  
  function version() {
    return $this->version;
  }
  
}
