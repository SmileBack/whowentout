<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Welcome extends MY_Controller
{
  
  function index() {
    $this->load->library('chat');
    
    $ven = user(array('first_name' => 'Venkat'));
    $dan = user(array('last_name' => 'Berenholtz'));
    $maggie = user(96);
    $claire = user(82);
    
//    var_dump($dan->is_online());
//    $this->chat->send($dan, $ven, 'offline', 'notice');
    $ids = $this->chat->chatted_with_user_ids($ven);
    var_dump($ids);
  }
  
}
