<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  public function index() {
    $user = XUser::get(2);
    $user->first_name = 'Venkat';
    $user->save();
  }
  
}
