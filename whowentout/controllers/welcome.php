<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  function index() {
    $this->load_view('test_chart_view');
  }
  
}
