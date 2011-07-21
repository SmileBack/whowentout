<?php

function job_unique_id() {
  return sha1( microtime(true) . mt_rand(10000, 90000) );
}

function job_call_async($function, $arg1 = NULL, $arg2 = NULL, $arg3 = NULL) {
  $args = func_get_args();
  $id = call_user_func_array('job_add', $args);
  job_run_async($id);
}

function job_add($function, $arg1 = NULL, $arg2 = NULL, $arg3 = NULL) {
  $args = func_get_args(); unset($args[0]);
  
  $row = array(
    'id' => job_unique_id(),
    'type' => $function,
    'created' => current_time()->getTimestamp(),
    'args' => serialize($args),
  );
  
  ci()->db->insert('jobs', $row);
  return $row['id'];
}

function job_delete($job_id) {
  ci()->db->delete('jobs', array('id' => $job_id));
}

function job_get($job_id) {
  ci()->db->from('jobs')->where('id', $job_id);
  $rows = ci()->db->get()->result();
  return empty($rows) ? NULL : $rows[0];
}

function job_pull($job_id) {
  $job = job_get($job_id);
  job_delete($job_id);
  return $job;
}

function job_run($job_id) {
  $job = job_pull($job_id);
  
  if (!$job)
    return FALSE;
  
  $job_function = $job->type;
  call_user_func_array( $job_function, unserialize($job->args) );
  
  return TRUE;
}

function job_run_async($job_id) {
  $ip = gethostbyname(gethostname());
  $job_url = "http://$ip/job/run/$job_id";
  job_curl_post_async($job_url);
}

function job_curl_post_async($url, $params = array())
{
    $post_params = array();
    foreach ($params as $key => &$val) {
      if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key.'='.urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts = parse_url($url);
    
    $fp = fsockopen($parts['host'],
                      isset($parts['port']) ? $parts['port'] : 80,
                      $errno, $errstr, 30);
    
    $out  = "POST ".$parts['path']." HTTP/1.1\r\n";
    $out .= "Host: ".$parts['host']."\r\n";
    $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out .= "Content-Length: " . strlen($post_string) . "\r\n";
    $out .= "Connection: Close\r\n\r\n";
    
    if (isset($post_string))
      $out .= $post_string;

    fwrite($fp, $out);
    fclose($fp);
    
    var_dump($errno, $errstr);
}
