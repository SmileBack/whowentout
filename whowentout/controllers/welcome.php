<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Welcome extends MY_Controller
{

    function index()
    {
        $this->load->library('chat');

        $party = party(11);

        $ven = user(array('first_name' => 'Venkat'));
        $dan = user(array('last_name' => 'Berenholtz'));

        $remi = user(97);
        $maggie = user(96);
        $claire = user(82);
        $jenny = user(108);

        $checkins_begin_time = college()->checkins_begin_time(TRUE);
        $checkins_end_time = college()->checkins_end_time(TRUE);

        var_dump($checkins_begin_time->format('Y-m-d H:i:s'));
        var_dump($checkins_end_time->format('Y-m-d H:i:s'));
    }

}
