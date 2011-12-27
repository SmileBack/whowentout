<?php

class JobQueue_Tests extends PHPUnit_Framework_TestCase
{

    /* @var $database Database */
    private $database;

    /* @var $installer PackageInstaller */
    private $installer;

    /* @var $job_queue JobQueue */
    private $job_queue;

    function setUp()
    {
        $factory = factory();

        $this->database = $factory->build('test_database');
        $this->installer = $factory->build('test_package_installer');

        $this->installer->install('JobQueuePackage');

        $this->job_queue = new JobQueue($this->database);
    }

    private function get_temp_filepath()
    {
        return tempnam(sys_get_temp_dir(), 'job_queue_test_');
    }

    function test_fetch_job()
    {
        // add a jobs A and B to the job queue
        $job_a = new TestJob(array(
            'path' => $this->get_temp_filepath(),
            'data' => 'a',
        ));

        $job_b = new TestJob(array(
            'path' => $this->get_temp_filepath(),
            'data' => 'b',
        ));

        $job_a_id = $this->job_queue->add($job_a);
        $job_b_id = $this->job_queue->add($job_b);


        // fetch the job A from the queue
        $fetched_job = $this->job_queue->fetch($job_a_id);

        // check that the fetched job is job A
        $this->assertEquals('a', $fetched_job->options['data']);

        // fetch job B from the queue
        $fetched_job = $this->job_queue->fetch($job_b_id);

        // check that the fetched job is job B
        $this->assertEquals('b', $fetched_job->options['data']);

        // destroy both jobs
        $this->job_queue->destroy($job_a_id);
        $this->job_queue->destroy($job_b_id);
    }

    function test_destroy_job()
    {
        // add a job to the job queue

        // destroy a job

        // check that the job no longer exists
    }

    function test_run_job()
    {
        // add a job to the job queue

        // run the job

        // check that the job has been run

        // destroy the job
    }

    function test_job_run_twice()
    {
        // add a job to the job qeuue

        // run the job

        // check that the job has been run

        // run the job again

        // check that the job hasn't been run a second time

        // destroy the job
    }

    function test_first_in_first_out()
    {
        // add job C and job D to the job queue

        // run the next job in the job queue

        // check that job C was run

        // run the next job in the job queue

        // check that job D was run
    }

}
