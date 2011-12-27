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

    function add(Job $job)
    {

    }

    /**
     * @param $job_id
     * @return Job
     */
    function fetch($job_id)
    {

    }

    function destroy($job_id)
    {

    }

}