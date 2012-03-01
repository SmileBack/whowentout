<?php

class EmailDealPlugin extends Plugin
{

    private $queue;

    function on_checkin($e)
    {
        $checkin = $e->checkin;

        $this->queue = build('job_queue');
    }
}
