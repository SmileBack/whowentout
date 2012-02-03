<?php

class SendEntourageRequestEmailPlugin extends Plugin
{

    function on_entourage_request_sent($e)
    {
        $request = $e->request;
        $this->send_email($request);
    }

    function send_email($request)
    {
        /* @var $queue JobQueue */
        $queue = build('job_queue');

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

        $queue->add($job);

        if ($request->receiver->email) // run job right away if you have their email
            $queue->run_in_background($job->id);
    }

}
