<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  function async() {
    $this->load->helper('job');
    $this->benchmark->mark('start');
    $id = job_call_async('send_email', current_user()->id, 'yoo', 'wsup');
    $this->benchmark->mark('end');
    print $this->benchmark->elapsed_time('start', 'end');
  }
  
  function noasync() {
    $this->benchmark->mark('start');
    send_email(current_user(), 'hello', 'there');
    $this->benchmark->mark('end');
    print $this->benchmark->elapsed_time('start', 'end');
  }
  
  function index() {
  }
  
}
