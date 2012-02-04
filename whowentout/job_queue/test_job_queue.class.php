<?php

class TestJobQueue
{
    private $jobs = array();

    /**
     * @param Job $job
     * @return Job
     */
    function add(Job $job)
    {
        assert($job->id == null);

        $job->id = $this->unique_token();
        $job->status = 'pending';

        $this->jobs[$job->id] = $job;

        return $job;
    }

    function update(Job $job)
    {
        assert($job->id != null);
        $this->jobs[$job->id] = $job;
    }

    /**
     * @param $job_id
     * @return Job
     */
    function fetch($job_id)
    {
        return isset($this->jobs[$job_id]) ? $this->jobs[$job_id] : null;
    }

    function destroy($job_id)
    {
        unset($this->jobs[$job_id]);
    }

    function run($job_id)
    {
        $job = $this->fetch($job_id);

        if ($job->status == 'pending') {
            $job->status = 'running';
            $this->update($job);

            try {
                $job->run();
            }
            catch (Exception $e) {
                $job->status = 'error';
                $this->update($job);
                return;
            }

            $job->status = 'complete';
            $this->update($job);
        }
    }

    function run_in_background($job_id)
    {
        $this->run($job_id);
    }

    private function unique_token()
    {
        return sha1(microtime(true) . mt_rand(10000, 90000));
    }

}
