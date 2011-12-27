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
        $this->database->destroy_all_tables();
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

        $this->job_queue->add($job_a);
        $this->job_queue->add($job_b);

        // fetch the job A from the queue
        $fetched_job = $this->job_queue->fetch($job_a->id);

        // check that the fetched job is job A
        $this->assertEquals('a', $fetched_job->options['data']);

        // check that the job has status of pending
        $this->assertEquals('pending', $fetched_job->status);

        // fetch job B from the queue
        $fetched_job = $this->job_queue->fetch($job_b->id);

        // check that the fetched job is job B
        $this->assertEquals('b', $fetched_job->options['data']);

        // destroy both jobs
        $this->job_queue->destroy($job_a->id);
        $this->job_queue->destroy($job_b->id);
    }

    function test_destroy_job()
    {
        // add a job to the job queue
        $job = $this->job_queue->add(new TestJob(array(
            'path' => $this->get_temp_filepath(),
            'data' => 9,
        )));

        $this->assertNotNull($this->job_queue->fetch($job->id), 'job exists');

        // destroy a job
        $this->job_queue->destroy($job->id);

        // check that the job no longer exists
        $this->assertNull($this->job_queue->fetch($job->id), 'job no longer exists');
    }

    function test_run_job()
    {
        // add a job to the job queue
        $path = $this->get_temp_filepath();
        $job = new TestJob(array(
            'path' => $path,
            'data' => 24,
        ));
        $this->job_queue->add($job);

        // run the job
        $this->job_queue->run($job->id);

        // check that the job has been run
        $data = @file_get_contents($path);
        $this->assertEquals(24, $data, 'job has been run');

        // check that the status is complete
        $this->assertEquals('complete', $this->job_queue->fetch($job->id)->status, 'job status is marked as complete');

        // destroy the job
        $this->job_queue->destroy($job->id);
    }

    function test_job_run_twice()
    {
        // add a job to the job qeuue
        $path = $this->get_temp_filepath();
        $job = new TestJob(array(
            'path' => $path,
            'data' => 'apples',
        ));
        $this->job_queue->add($job);

        // run the job
        $this->job_queue->run($job->id);

        // check that the job has been run
        $data = @file_get_contents($path);
        $this->assertEquals('apples', $data);
        @unlink($path);

        // run the job again
        $this->job_queue->run($job->id);

        // check that the job hasn't been run a second time
        $this->assertFalse(file_exists($path));

        // destroy the job
        $this->job_queue->destroy($job->id);
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
