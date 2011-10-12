<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ping extends MY_Controller
{

    function offline()
    {
        if (!logged_in())
            show_404();

        $this->load->library('presence');
        $presence_token = post('presence_token');
        $this->presence->ping_offline(current_user()->id, $presence_token);
    }

    function active()
    {
        if (!logged_in())
            show_404();

        $this->load->library('presence');
        $presence_token = post('presence_token');
        $this->presence->ping_active(current_user()->id, $presence_token);
    }

}
