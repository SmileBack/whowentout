<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{
    
    function index()
    {
    }

    function test_email()
    {
        job_call_async('send_email', current_user(), 'hello ' . rand(1, 100), 'hello here is a random number ' . rand(1, 100));
    }
    
}
