<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  function index() {
    $user = user(array('first_name' => 'Venkat'));
    $user->refresh_image('facebook');
  }
  
}
