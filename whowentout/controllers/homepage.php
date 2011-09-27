<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Homepage extends MY_Controller
{

    function index()
    {
        if (logged_in())
            redirect('dashboard');

        $user = current_user();
        $college = college();
        $current_time = current_time();

        $this->load->view('homepage_view');
    }

}
