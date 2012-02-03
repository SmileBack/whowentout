<?php

class Emailer extends Component
{

    public function send_email($email, $subject, $body)
    {
        $this->driver->send_email($email, $email, $subject, $body);
    }

}
