<?php

function send_email($to, $subject, $body) {
  $ci =& get_instance();
  return $ci->email->send($to, $subject, $body);
}
