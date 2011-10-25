<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Email extends Component
{

    protected $ci;

    function __construct($config)
    {
        parent::__construct($config);

        $this->ci =& get_instance();
        $this->ci->load->helper('email');
    }

    function send($to, $subject, $body)
    {
        if (is_int($to))
            $to = XUser::get($to);

        if (is_array($to))
            $to = (object)$to;

        if ($to == NULL || $to->email == NULL)
            return FALSE;

        $this->driver()->send_email($to, $subject, $body);
        $this->ci->db->insert('sent_emails', array(
                                                  'recipient_email' => $to->email,
                                                  'subject' => $subject,
                                                  'body' => $body,
                                             ));
    }

}
