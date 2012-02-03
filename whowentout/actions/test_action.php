<?php

class TestAction extends Action
{

    function execute()
    {

        $invite = db()->table('invites')->row(15);
        $request = db()->table('entourage_requests')->row(47);


    }

    function send_email_for_entourage_request($request)
    {
        /* @var $queue JobQueue */
        $queue = build('job_queue');

        $subject = r::entourage_invite_email_subject(array(
            'request' => $request,
        ))->render();

        $job = new SendEmailJob(array(
            'user_id' => $request->receiver->id,
            'subject' => $subject,
            'body' => r::email(array(
                'body' => r::entourage_invite_email(array(
                    'request' => $request,
                )),
            ))->render(),
        ));

        $queue->add($job);
    }

}
