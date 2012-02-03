<?php

class SendEmailJob extends Job
{

    public $required_options = array('user_id', 'subject', 'body');

    /* @var $emailer Emailer */
    private $emailer;

    /* @var $db Database */
    private $db;

    function __construct($options = array())
    {
        parent::__construct($options);
        $this->emailer = build('emailer');
        $this->db = db();
    }

    function run()
    {
        $user = $this->db->table('users')->row($this->options['user_id']);
        $this->emailer->send_email(
            $user->email,
            $this->options['subject'],
            $this->options['body']
        );
    }

}
