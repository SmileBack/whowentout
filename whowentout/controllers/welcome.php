<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  function index() {
    $this->load_view('test_chart_view');
//    print college()->party_day(1, TRUE)->format('Y-m-d H:i:s');
  }
  
}
