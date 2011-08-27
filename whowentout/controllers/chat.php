<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends MY_Controller
{
  
  function messages($version) {
    $this->load->library('chat');
    
    $user = current_user();
    
    $messages = $this->chat->messages( current_user(), $version );
    foreach ($messages as &$message) {
      $message->sender = user($message->sender_id)->to_array();
      $message->receiver = user($message->receiver_id)->to_array();
    }
    
    $response = array(
      'messages' => $messages,
      'version' => $this->chat->version(),
    );
    
    print json_encode($response);
  }
  
  function send() {
    $this->load->library('chat');
    
    $from = current_user();
    $to = user( post('to') );
    $message = post('message');
    
    $this->chat->send($from, $to, $message);
    
    print 'done';exit;
  }
  
  function mark_read() {
    $this->load->library('chat');
    
    $from = user( post('from') );
    
    $this->chat->mark_as_read(current_user(), $from);
    
    print 'done';exit;
  }
  
}
