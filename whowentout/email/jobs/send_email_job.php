<?php

class SendEmailJob extends Job
{

    public $required_options = array('email', 'subject', 'body');

    /* @var $emailer Emailer */
    private $emailer;

    function __construct($options = array())
    {
        parent::__construct($options);
        $this->emailer = build('emailer');
    }

    function run()
    {
        $this->emailer->send_email(
            $this->options['email'],
            $this->options['subject'],
            $this->options['body']
        );
    }

}
