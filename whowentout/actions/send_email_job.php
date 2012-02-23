<?php

class SendEmailJob extends Job
{

    public $required_options = array('subject', 'body');

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
        $email = $this->get_target_email();
        $this->emailer->send_email(
            $email,
            $this->options['subject'],
            $this->options['body']
        );
    }

    function get_target_email()
    {
        if (isset($this->options['email'])) {
            return $this->options['email'];
        }
        elseif (isset($this->options['user_id'])) {
            $user = $this->db->table('users')->row($this->options['user_id']);
            return $user->email;
        }
    }

}
