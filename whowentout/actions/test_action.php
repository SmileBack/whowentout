<?php

class TestAction extends Action
{

    function execute_update_emails()
    {
        /* @var $queue JobQueue */
        $queue = build('job_queue');

        $users_without_email = db()->table('users')->where('last_login', null, '!=')
                                     ->where('email', null);

        /* @var $user DatabaseRow */
        foreach ($users_without_email as $user) {
            $job = new UpdateFacebookProfileJob(array('user_id' => $user->id));
            $queue->add($job);
            $queue->run_in_background($job->id);
        }
    }
    
}
