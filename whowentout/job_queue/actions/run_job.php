<?php

class RunJobAction extends Action
{

    /* @var $job_queue JobQueue */
    private $job_queue;

    function __construct()
    {
        $this->job_queue = build('job_queue');
    }

    function execute($job_id)
    {
        $this->job_queue->run($job_id);

        print json_encode(array('success' => TRUE));
        exit;
    }

}
