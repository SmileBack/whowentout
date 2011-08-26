<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Welcome extends MY_Controller
{
  
  function index() {
    $this->load->library('chat');
    
    $ven = user(array('first_name' => 'Venkat'));
    $dan = user(array('last_name' => 'Berenholtz'));
    
    $this->chat->send($dan, $ven, 'a newww message');
    //$this->chat->send($ven, $dan, 'yoyoyoyo');
  }
  
}
