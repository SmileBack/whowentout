<?php

class Test extends MY_Controller
{
  
  function index() {
    $this->load->library('tester');
    
    $groups = $this->tester->groups();
    
    $this->load->view('tester/tests', array('groups' => $groups));
  }
  
  function group($group = NULL) {
    $this->load->library('tester');
    
    $exists = $this->tester->load($group);
    
    if ( ! $exists )
      show_404();
    
    $this->tester->run();
    
    $report = $this->tester->report();
    
    $this->load->view('tester/report', array('report' => $report));
  }
  
}