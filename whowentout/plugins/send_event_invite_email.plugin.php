<?php

class SendEventInviteEmailPlugin extends Plugin
{

    /* @var $queue JobQueue */
    private $queue;

    function on_event_invites_sent($e)
    {
        $this->queue = build('job_queue');

        $invite = $e->invite;
        $this->send_email($invite);
        $this->notify_admins($invite);
    }

    function notify_admins($invite)
    {
        app()->notify_admins('event invite', format::full_name($invite->sender)
                . ' to ' . format::full_name($invite->receiver));
    }

    function send_email($invite)
    {
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

        $this->queue->add($job);

        if ($invite->receiver->email) // run job right away if you have their email
            $this->queue->run_in_background($job->id);
    }

}
