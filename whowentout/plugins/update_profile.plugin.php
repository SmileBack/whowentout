<?php

class UpdateProfilePlugin extends Plugin
{
    function on_after_request($e)
    {
        if (string_starts_with('jobs/', $e->url)) // don't want infinite recursion !
            return;

        if (!auth()->logged_in())
            return;

        if ($this->facebook_profile_is_outdated()) {
            $this->update_facebook_profile_in_background();
        }
    }

    function update_facebook_profile_in_background()
    {
        /* @var $job_queue JobQueue */
        $job_queue = build('job_queue');
        $job = new UpdateFacebookProfileJob(array(
            'user_id' => auth()->current_user()->id,
        ));
        $job_queue->add($job);
        $job_queue->run_in_background($job->id);
    }

    private function facebook_profile_is_outdated()
    {
        $current_time = app()->clock()->get_time();

        /* @var $last_update DateTime */
        $last_update = auth()->current_user()->facebook_profile_last_update;

        if ($last_update == null)
            return true;

        $next_update = clone $last_update;
        $next_update->modify('+6 hours');

        // next required update is in the past
        return $current_time > $next_update;
    }

}
