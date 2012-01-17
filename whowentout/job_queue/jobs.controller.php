<?php

class Jobs_Controller extends Controller
{

    /* @var $job_queue JobQueue */
    private $job_queue;

    function __construct()
    {
        parent::__construct();

        $this->job_queue = build('job_queue');

        js()->pusher_settings = array(
            'key' => '1234567',
        );
    }

    function run($job_id)
    {
        $this->job_queue->run($job_id);

        print json_encode(array('success' => TRUE));
        exit;
    }

    function client()
    {
        print r::job_client();
    }

}
