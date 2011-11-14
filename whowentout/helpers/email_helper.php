<?php

function send_email($to, $subject, $body)
{
    /* @var $email Emailer */
    $email = f()->fetch('emailer');
    $email->send($to, $subject, $body);
}
