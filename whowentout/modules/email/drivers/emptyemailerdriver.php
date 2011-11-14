<?php

class EmptyEmailerDriver extends EmailerDriver
{

    function send_email($to, $subject, $body)
    {
    }

}
