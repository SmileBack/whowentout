<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  function index() {
    print render('dashboard', array(
      'first_name' => 'Yeaha',
    ));
  }
  
}
