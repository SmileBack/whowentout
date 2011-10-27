<?php

class Job_Proxy extends MY_Controller
{
    
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        print r('job_proxy_page');
    }

}