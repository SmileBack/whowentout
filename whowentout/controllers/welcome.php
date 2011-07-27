<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  function index() {
    update_facebook_friends(array('first_name' => 'Dan', 'last_name' => 'Berenholtz'));
    update_facebook_friends(array('first_name' => 'Venkat', 'last_name' => 'Dinavahi'));
  }
  
}
