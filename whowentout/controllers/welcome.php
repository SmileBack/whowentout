<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  public function index() {
    $user = current_user();
    $user->update_facebook_data();
//    $user = current_user();
//    $data = $user->fetch_facebook_data();
//    var_dump( $data['education'] );
  }
  
}
