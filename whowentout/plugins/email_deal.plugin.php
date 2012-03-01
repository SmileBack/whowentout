<?php

class EmailDealPlugin extends Plugin
{

    /**
     * @var JobQueue
     */
    private $queue;

    function on_checkin($e)
    {
        $this->queue = build('job_queue');

        $checkin = $e->checkin;

        $job = new EmailDealJob(array('checkin_id' => $checkin->id));
        $this->queue->add($job);

        $this->queue->run_in_background($job->id);
    }

}
