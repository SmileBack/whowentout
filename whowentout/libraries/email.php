<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Email
{

    protected $config = array();
    protected $driver_config = array();
    protected $ci;
    protected $driver;

    function __construct()
    {
        $this->ci =& get_instance();
        $this->load_config();
        $this->load_driver();

        $this->ci->load->helper('email');
    }

    private function load_config()
    {
        $this->ci->load->config('email');
        $this->config = $this->ci->config->item('email');
        $this->driver_config = $this->config[$this->config['active_group']];
    }

    private function load_driver()
    {
        $driver_name = $this->driver_config['driver'];
        $base_driver_path = APPPATH . "libraries/email/emaildriver.php";
        $driver_path = APPPATH . "libraries/email/drivers/{$driver_name}emaildriver.php";
        require_once $base_driver_path;
        require_once $driver_path;

        $driver_class_name = "{$driver_name}emaildriver";
        $this->driver = new $driver_class_name($this->driver_config);
    }

    function send($to, $subject, $body)
    {
        if (is_int($to))
            $to = user($to);

        if (is_array($to))
            $to = (object)$to;

        if ($to == NULL || $to->email == NULL)
            return FALSE;
    
        $this->driver->send_email($to, $subject, $body);
        $this->ci->db->insert('sent_emails', array(
                                                  'recipient_email' => $to->email,
                                                  'subject' => $subject,
                                                  'body' => $body,
                                             ));
    }

}
