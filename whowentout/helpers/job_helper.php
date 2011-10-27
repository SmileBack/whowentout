<?php

function job_unique_id()
{
    return sha1(microtime(true) . mt_rand(10000, 90000));
}

function job_call_async($function, $arg1 = NULL, $arg2 = NULL, $arg3 = NULL)
{
    $args = func_get_args();
    $id = call_user_func_array('job_add', $args);
    job_run_async($id);
}

function job_add($function, $arg1 = NULL, $arg2 = NULL, $arg3 = NULL)
{
    $args = func_get_args();
    unset($args[0]);

    $row = array(
        'id' => job_unique_id(),
        'status' => 'pending',
        'type' => $function,
        'created' => time(),
        'args' => serialize($args),
    );

    ci()->db->insert('jobs', $row);
    return $row['id'];
}

function job_delete($job_id)
{
    ci()->db->delete('jobs', array('id' => $job_id));
}

function job_get($job_id)
{
    ci()->db->from('jobs')->where('id', $job_id);
    $rows = ci()->db->get()->result();

    if (empty($rows))
        return NULL;

    $rows[0]->args = unserialize($rows[0]->args);
    return $rows[0];
}

function job_run($job_id)
{
    $job = job_get($job_id);
    $exception = NULL;

    if (!$job)
        return FALSE;

    try {
        if (!function_exists($job->type))
            throw new Exception("Job type $job->type doesn't exist.");

        call_user_func_array($job->type, $job->args);
        ci()->db->where('id', $job->id)
                ->update('jobs', array(
                                      'status' => 'complete',
                                      'executed' => time(),
                                 ));
    }
    catch (Exception $e) {
        ci()->db->where('id', $job->id)
                ->update('jobs', array(
                                      'status' => 'error',
                                      'executed' => time(),
                                      'error_message' => $e->getMessage(),
                                      'error_line' => $e->getLine(),
                                      'error_file' => $e->getFile(),
                                      'error' => serialize($e),
                                 ));
    }

    return TRUE;
}

function job_run_async($job_id)
{
    $job_url = site_url("job/run/$job_id");
    serverchannel()->trigger('job_proxy', 'new_job', array(
                                                          'url' => $job_url,
                                                     ));
    //  job_curl_post_async($job_url);
}

function job_curl_post_async($url, $params = array())
{
    krumo::dump(array('url' => $url, 'params' => $params));

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
