<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Homepage extends MY_Controller
{

    function index()
    {
        if (logged_in())
            redirect('dashboard');
        
        print r('homepage_view');
    }

}
