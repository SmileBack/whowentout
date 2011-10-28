<?php

class EmailErrorsPlugin extends Plugin
{

    function on_error($e)
    {
        $this->send_error_email($e->message);
        $this->send_email('errors@whowentout.com', substr($e->message, 20), $e->message);
    }

    function send_error_email($message)
    {
        job_call_async('send_email', substr($message, 20), $message);
    }

}
