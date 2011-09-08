<?php

class MY_Controller extends CI_Controller {
  
  function __construct() {
    parent::__construct();
    
    require_once APPPATH . 'libraries/aws/sdk.class.php';
    require_once APPPATH . 'libraries/imagerepository.php';
    require_once APPPATH . 'libraries/fb/testfacebook.php';
    
    require_once APPPATH . 'objects/xobject.php';
    require_once APPPATH . 'objects/xuser.php';
    require_once APPPATH . 'objects/xcollege.php';
    require_once APPPATH . 'objects/xparty.php';
    require_once APPPATH . 'objects/xplace.php';
  }
  
  protected function load_view($name, $data = array()) {
    $this->load->view('header', $data);
    $this->load->view($name, $data);
    $this->load->view('footer', $data);
  }
  
}
