<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  function index() {
    $user = user(array('first_name' => 'Bobby', 'last_name' => 'Dole'));
    var_dump($user);
    $attachment =  array(
      'message' => "$user->full_name checked into Sig Chi on WhoWentOut",
      'link' => "http://www.whowentout.com",
      'caption' => "Connecting people after a night out.",
    );
    fb()->api("/$user->facebook_id/feed", 'POST', $attachment);
  }
  
}
