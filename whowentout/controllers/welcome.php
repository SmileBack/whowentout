<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  function index() {
    $this->session->sess_destroy();
  }
  
  function index2() {
  }
  
  function ajax() {
    $response = $_COOKIE;
    print json_encode($response);exit;
  }
  
  function regen() {
  }
  
}
