<?php

abstract class EmailerDriver extends Driver
{
    abstract function send_email($recipient_name, $recipient_email, $subject, $body);
}
