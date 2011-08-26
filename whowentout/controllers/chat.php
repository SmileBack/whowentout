<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends MY_Controller
{
  
  function messages($version) {
    $this->load->library('chat');
    
    $user = current_user();
    
    $response = array(
      'messages' => $this->chat->messages( current_user(), $version ),
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
  }
  
}
