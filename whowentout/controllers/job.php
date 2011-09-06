<?php

class Job extends MY_Controller
{
  
  function __construct() {
    parent::__construct();
    
    $this->load->library('email');
  }
  
  function run($job_id) {
    job_run($job_id);
    $this->session->sess_destroy();
  }
  
}
