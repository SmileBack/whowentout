<?php

class Job extends MY_Controller
{
  
  function run($job_id) {
    job_run($job_id);
    $this->session->sess_destroy();
  }
  
}
