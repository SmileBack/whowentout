<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{
  
  function index() {
    $this->load_view('emails/smile_received_email', array(
      'party' => party(11),
      'sender' => user(array('first_name' => 'Venkat')),
      'receiver' => user(array('first_name' => 'Ava')),
      'date' => current_time(TRUE),
    ));
    
  }
  
  function index2() {
    print substr('alexander', 0, 2);
  }
  
}
