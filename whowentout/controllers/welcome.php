<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  function index() {
    print fb()->getAccessToken();
//    print get_option('admin_facebook_access_token');
//    $this->load_view('test_chart_view');
//    print college()->party_day(1, TRUE)->format('Y-m-d H:i:s');
  }
  
}
