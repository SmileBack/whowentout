<?php

class JobQueue
{

    /* @var $database Database */
    private $database;

    /* @var $jobs DatabaseTable */
    private $jobs;

    function __construct(Database $database)
    {
        $this->database = $database;
        $this->jobs = $this->database->table('jobs');
    }

    /**
     * @param Job $job
     * @return Job
     */
    function add(Job $job)
    {
        assert($job->id == null);

        $job->id = $this->unique_token();
        $job->status = 'pending';

        $this->jobs->create_row(array(
            'id' => $job->id,
            'type' => get_class($job),
            'status' => $job->status,
            'options' => serialize($job->options),
        ));

        return $job;
    }

    function update(Job $job)
    {
        assert($job->id != null);

        $job_row = $this->jobs->row($job->id);
        $job_row->status = $job->status;
        $job_row->options = serialize($job->options);

        $job_row->save();
    }

    /**
     * @param $job_id
     * @return Job
     */
    function fetch($job_id)
    {
        $job_row = $this->jobs->row($job_id);

        if (!$job_row)
            return null;

        $options = unserialize($job_row->options);

        $job = $this->init_job($job_row->type, $options);
        $job->id = $job_row->id;
        $job->status = $job_row->status;

        return $job;
    }

    function destroy($job_id)
    {
        $this->jobs->destroy_row($job_id);
    }

    function run($job_id)
    {
        $job = $this->fetch($job_id);

        if ($job->status == 'pending') {
            $job->run();
            $job->status = 'complete';

            $this->update($job);
        }
    }

    /**
     * @param $type
     * @param $options
     * @return Job
     */
    private function init_job($type, $options)
    {
        $job = new $type($options);
        return $job;
    }

    private function unique_token()
    {
        return sha1(microtime(true) . mt_rand(10000, 90000));
    }

}
