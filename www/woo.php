<?php

function job_curl_post_async($url, $params = array())
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

    $out  = "POST ".$parts['path']." HTTP/1.1\r\n";
    $out .= "Host: ".$parts['host']."\r\n";

    $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out .= "Content-Length: " . strlen($post_string) . "\r\n";
    $out .= "Connection: Close\r\n\r\n";

    if (isset($post_string))
      $out .= $post_string;

    fwrite($fp, $out);
    fclose($fp);
}

error_reporting(E_ALL);

$url = 'http://www.whowasout.com/job/run/5ee2254d3fed2d10e7a4af28d3aaa7321cf8b340';
$params = array();
job_curl_post_async($url, $params);
