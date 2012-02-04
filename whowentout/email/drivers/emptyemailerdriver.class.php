<?php

class EmptyEmailerDriver extends EmailerDriver
{
    function send_email($recipient_name, $recipient_email, $subject, $body)
    {
        // do nothing
    }
}
