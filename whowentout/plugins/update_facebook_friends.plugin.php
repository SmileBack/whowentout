<?php

class UpdateFacebookFriendsPlugin extends Plugin
{

    function on_before_request($e)
    {
        if (string_starts_with('jobs/', $e->url)) // don't want infinite recursion !
            return;

        if (!auth()->logged_in())
            return;

        if ($this->facebook_friends_are_outdated())
            $this->update_facebook_friend_in_background();
    }

    private function update_facebook_friend_in_background()
    {
        /* @var $job_queue JobQueue */
        $job_queue = build('job_queue');
        $job = new UpdateFacebookFriendsJob(array(
            'user_id' => auth()->current_user()->id,
        ));
        $job_queue->add($job);
        $job_queue->run_in_background($job->id);
    }

    private function facebook_friends_are_outdated()
    {
        $current_time = app()->clock()->get_time();

        /* @var $last_update DateTime */
        $last_update = auth()->current_user()->facebook_friends_last_update;

        if ($last_update == null)
            return true;

        $next_update = clone $last_update;
        $next_update->modify('+6 hours');

        // next required update is in the past
        return $current_time > $next_update;
    }

}
