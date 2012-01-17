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

    function run_in_background($job_id)
    {
        $run_job_url = $this->run_job_url($job_id);
        $this->post_async_pusher($run_job_url);
//        $this->post_async($run_job_url);
    }

    private function run_job_url($job_id)
    {
        return site_url("jobs/run/$job_id");
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

    private function post_async_pusher($url, $params = array())
    {
        /* @var $pusher Pusher */
        $pusher = build('pusher');
        $pusher->trigger('job_queue', 'new_job', array(
            'url' => $url,
            'params' => $params,
        ));
    }

    private function post_async($url, $params = array())
    {
        $post_params = array();
        foreach ($params as $key => &$val) {
            if (is_array($val)) $val = implode(',', $val);
            $post_params[] = $key . '=' . urlencode($val);
        }
        $post_string = implode('&', $post_params);
        $parts = parse_url($url);

        $fp = fsockopen($parts['host'],
            isset($parts['port']) ? $parts['port'] : 80,
            $errno, $errstr, 30);

        $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";

        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-Length: " . strlen($post_string) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";

        if (isset($post_string))
            $out .= $post_string;

        fwrite($fp, $out);
        fclose($fp);
    }

}
