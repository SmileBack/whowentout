<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  public function index() {
    $user = XUser::get(array('first_name' => 'Robert'));
    $user->download_facebook_pic();
  }
  
}
