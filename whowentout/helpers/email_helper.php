<?php

function send_email($to, $subject, $body)
{
    $ci =& get_instance();
    $ci->load->library('xemail');
    return $ci->xemail->send($to, $subject, $body);
}
