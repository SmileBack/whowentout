<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  function index() {
    $this->load->helper('chat');
    $ven = user(array('first_name' => 'Venkat'));
    $dan = user(array('last_name' => 'Berenholtz'));
    chat_send_message($dan, $ven, 'leave me alone');
  }
  
  function a() {
    $this->load->helper('chat');
    $ven = user(array('first_name' => 'Venkat'));
    $dan = user(array('last_name' => 'Berenholtz'));
    var_dump(chat_new_messages($ven, $dan));
  }
  
  
}
