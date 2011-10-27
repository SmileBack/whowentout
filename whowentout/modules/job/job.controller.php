<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Job extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->library('email');
    }

    function run($job_id)
    {
        job_run($job_id);
        $job = job_get($job_id);
        $this->session->sess_destroy();

        $this->json(array(
                        'success' => TRUE,
                        'job' => $job,
                    ));
    }

}
