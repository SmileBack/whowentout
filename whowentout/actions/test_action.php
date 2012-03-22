<?php

class TestJob extends Job
{
    function run()
    {
    }
}

class TestAction extends Action
{

    function execute()
    {
        /* @var $queue JobQueue */
        $queue = build('job_queue');

        $job = $queue->add(new TestJob());
        $queue->run_in_background($job->id);
    }
    
}
