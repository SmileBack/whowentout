<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends MY_Controller
{
  
  function messages($version) {
    $this->load->library('chat');
    
    $user = current_user();
    
    $messages = $this->chat->messages( current_user(), $version );
    $users = array();
    
    foreach ($messages as &$message) {
      $sender = user($message->sender_id);
      $receiver = user($message->receiver_id);
      $users[$sender->id] = $sender->to_array();
      $users[$receiver->id] = $receiver->to_array();
    }
    
    $response = array(
      'messages' => $messages,
      'version' => $this->chat->version(),
      'users' => $users,
    );
    
    print json_encode($response);exit;
  }
  
  function send() {
    $this->load->library('chat');
    
    $from = current_user();
    $to = user( post('to') );
    $message = post('message');
    
    if ($to->is_online()) {
      $this->chat->send($from, $to, $message);
      $response = array(
        'success' => TRUE,
      );
    }
    else {
      $response = array(
        'success' => FALSE,
        'message' => "<p>Message wasn't delivered because $to->full_name is offline.</p>"
      );
    }
    
    print json_encode($response);exit;
  }
  
  function mark_read() {
    $this->load->library('chat');
    
    $from = user( post('from') );
    
    $this->chat->mark_as_read(current_user(), $from);
    
    print 'done';exit;
  }
  
  function save_chatbar_state() {
    $user = current_user();
    $state = post('chatbar_state');
    $user->chatbar_state = json_encode($state);
    $user->save();
    
    print 'done';exit;
  }
  
}
