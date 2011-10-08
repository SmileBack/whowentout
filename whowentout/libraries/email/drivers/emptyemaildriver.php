<?php

class EmptyEmailDriver extends EmailDriver
{

    function send_email($to, $subject, $body)
    {
    }

}
