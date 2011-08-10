<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends MY_Controller {
  
  function index() {
    $this->load_view('test_chart_view');
  }
  
  function send() {
    $this->load->helper('chat');
    
    $from = current_user();
    $to = post('user_id');
    $message = post('message');
    chat_send_message($from, $to, $message);
  }
  
  function get() {
    $this->load->helper('chat');
    
    $from = current_user();
    $to = post('user_id');
    $messages = chat_new_messages($from, $to);
    
    $response = array(
      'messages' => $messages,
    );
    print json_encode($response);exit;
  }
  
}
