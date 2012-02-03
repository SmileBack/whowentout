<?php

class TestAction extends Action
{

    function execute_asdfds()
    {
        $invite = db()->table('invites')->row(15);
        print r::event_invite_email(array(
            'invite' => $invite,
        ));
    }

    function execute()
    {
        /* @var $queue JobQueue */
        $queue = build('job_queue');

        $invite = db()->table('invites')->row(15);

        $title = r::invite_email_title(array(
            'invite' => $invite,
        ))->render();

        $job = new SendEmailJob(array(
            'user_id' => 1,//8482,
            'subject' => $title,
            'body' => r::email(array(
                'title' => "<h1>$title</h1>",
                'body' => r::invite_email_body(array(
                    'invite' => $invite,
                )),
            ))->render(),
        ));

        $queue->add($job);
    }

}
