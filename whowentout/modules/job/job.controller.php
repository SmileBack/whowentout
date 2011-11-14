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

    function pending()
    {
        $jobs = $this->db->from('jobs')
                ->where('status', 'pending')
                ->order_by('type', 'asc')
                ->get()->result();
        $this->load_view('job_list', array('jobs' => $jobs));
    }

    function admin_run_pending()
    {
        $this->require_admin();

        $jobs = $this->db->from('jobs')
                ->where('status', 'pending')
                ->order_by('type', 'asc')
                ->get()->result();

        foreach ($jobs as $job) {
            job_run_async($job->id);
        }

        set_message("Ran " . count($jobs) . " jobs.");
        redirect('job/pending');
    }

    function admin_run($job_id)
    {
        $this->require_admin();

        job_run_async($job_id);
        set_message("Ran job $job_id");
        redirect('job/pending');
    }

}
