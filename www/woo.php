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
ini_set('display_errors', '1');

$url = 'http://www.whowasout.com/job/run/71ac2f2dbd5234abdf546043764b025f661e4c3f';
$params = array();
file_get_contents($url);
//job_curl_post_async($url, $params);
