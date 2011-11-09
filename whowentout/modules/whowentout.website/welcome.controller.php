<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function index()
    {
        $logger = new UserEventLogger($this->db);
        $logger->log(current_user(), college()->get_time(), 'test', array(1, 2, 3));
    }
    
    function test($name)
    {
        $emails = array('ven' => 'vendiddy@gmail.com',
                        'berenholtzdan@gmail.com',
                        'ventxt' => '4438569502@txt.att.net');

        if (isset($emails[$name])) {
            $email = $emails[$name];
            job_call_async('send_email', $email, 'hello ' . rand(1, 100), 'hello here is a random number ' . rand(1, 100));
            print "<h3>sent email to $email</h3>";
        }
        else {
            print "<h3>no such email</h3>";
        }
    }

}
