<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Chat
{
  
  private $ci;
  private $version;
  private $last_query;
  
  function __construct() {
    $this->ci =& get_instance();
    $this->db = $this->ci->db;
  }
  
  function send($sender_id, $receiver_id, $message, $type = 'normal') {
    $sender = user($sender_id);
    $receiver = user($receiver_id);
    $this->db->insert('chat_messages', array(
      'type' => $type,
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
    
    $this->last_query = $this->db->last_query();
    
    return $messages;
  }
  
  function chatted_with_user_ids($from) {
    $ids = array();
    $from = user($from);
    
    $rows = $this->db->select('sender_id')
                     ->distinct()
                     ->from('chat_messages')
                     ->where('receiver_id', $from->id)
                     ->get()->result();
    
    foreach ($rows as $row) {
      $ids[] = $row->sender_id;
    }
    
    $rows = $this->db->select('receiver_id')
                     ->distinct()
                     ->from('chat_messages')
                     ->where('sender_id', $from->id)
                     ->get()->result();
    
    foreach ($rows as $row) {
      $ids[] = $row->receiver_id;
    }
    
    return array_unique($ids);
  }
  
  function mark_as_read($by, $from) {
    $from = user($from);
    $by = user($by);
    
    $this->db->where('receiver_id', $by->id)
             ->where('sender_id', $from->id)
             ->update('chat_messages', array('is_read' => 1));
    
    $this->last_query = $this->db->last_query();
  }
  
  function version() {
    return $this->version;
  }
  
  function last_query() {
    return $this->last_query;
  }
  
}
