<?php

class Jobs_Controller extends Controller
{

    /* @var $job_queue JobQueue */
    private $job_queue;

    function __construct()
    {
        parent::__construct();

        $this->job_queue = factory()->build('job_queue');
    }

    function test()
    {
        $path = 'C:\\test.txt';
        $job = new TestJob(array(
            'path' => $path,
            'data' => 'oranges',
        ));
        $this->job_queue->add($job);

        // run the job in the background
        $this->job_queue->run_in_background($job->id);
    }

    function run($job_id)
    {
        $this->job_queue->run($job_id);

        print json_encode(array('success' => TRUE));
        exit;
    }

}
