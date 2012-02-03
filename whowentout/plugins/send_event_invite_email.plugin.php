<?php

class SendEventInviteEmailPlugin extends Plugin
{

    function on_event_invite_sent($e)
    {
        $invite = $e->invite;
        $this->send_email($invite);
    }

    function send_email($invite)
    {
        /* @var $queue JobQueue */
        $queue = build('job_queue');

        $subject = r::invite_email_subject(array(
            'invite' => $invite,
        ))->render();

        $job = new SendEmailJob(array(
            'user_id' => $invite->receiver->id,
            'subject' => $subject,
            'body' => r::email(array(
                'body' => r::invite_email(array(
                    'invite' => $invite,
                )),
            ))->render(),
        ));

        $queue->add($job);

        if ($invite->receiver->email) // run job right away if you have their email
            $queue->run_in_background($job->id);
    }

}
