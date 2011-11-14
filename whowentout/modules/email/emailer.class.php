<?php

class Emailer extends Component
{

    function __construct($config)
    {
        parent::__construct($config);
    }

    function send($to, $subject, $body)
    {
        if (is_string($to))
            $to = array('email' => $to);

        if (is_int($to))
            $to = XUser::get($to);

        if (is_array($to))
            $to = (object)$to;

        if ($to == NULL || $to->email == NULL)
            return FALSE;

        $this->driver()->send_email($to, $subject, $body);
        $this->db()->insert('sent_emails', array(
                                                  'recipient_email' => $to->email,
                                                  'subject' => $subject,
                                                  'body' => $body,
                                             ));
    }

    private function db()
    {
        $ci =& get_instance();
        return $ci->db;
    }

}
