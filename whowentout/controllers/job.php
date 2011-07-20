<?php

class Job extends MY_Controller
{
  
  function run($job_id) {
    $this->load->helper('job');
    job_run($job_id);
  }
  
}
