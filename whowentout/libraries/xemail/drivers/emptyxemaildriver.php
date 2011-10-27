<?php

class EmptyXEmailDriver extends XEmailDriver
{

    function send_email($to, $subject, $body)
    {
    }

}
