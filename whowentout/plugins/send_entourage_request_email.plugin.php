<?php

class SendEntourageRequestEmailPlugin extends Plugin
{

    /* @var $queue JobQueue */
    private $queue;

    function on_entourage_request_sent($e)
    {
        $this->queue = build('job_queue');
        $request = $e->request;

        $this->send_email($request);
        $this->notify_admin($request);
    }

    function notify_admin($request)
    {
        app()->notify_admins('entourage request', format::full_name($request->sender)
                . ' to ' . format::full_name($request->receiver));
    }

    function send_email($request)
    {
        $subject = r::entourage_request_email_subject(array(
            'request' => $request,
        ))->render();

        $job = new SendEmailJob(array(
            'user_id' => $request->receiver->id,
            'subject' => $subject,
            'body' => r::email(array(
                'body' => r::entourage_request_email(array(
                    'request' => $request,
                )),
            ))->render(),
        ));

        $this->queue->add($job);

        if ($request->receiver->email) // run job right away if you have their email
            $this->queue->run_in_background($job->id);
    }

}
