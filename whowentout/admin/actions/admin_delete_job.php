<?php

class AdminDeleteJobAction extends Action
{

    /* @var $job_queue JobQueue */
    private $job_queue;

    function __construct()
    {
        $this->job_queue = build('job_queue');
    }

    function execute()
    {
        auth()->require_admin();

        $job_id = $_POST['job_id'];
        $this->job_queue->update_status($job_id, 'deleted');

        flash::message("Deleted job $job_id");

        redirect('admin/emails');
    }

}
