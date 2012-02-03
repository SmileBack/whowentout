<?php

class TestAction extends Action
{

    function execute()
    {
        /* @var $queue JobQueue */
        $queue = build('job_queue');

        $job = new SendEmailJob(array(
            'user_id' => 1,
            'subject' => 'venn',
            'body' => 'kattt',
        ));

        $queue->add($job);
    }

}
