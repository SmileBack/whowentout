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

        $insert_positions = $party->attendee_insert_positions($remi);
        var_dump($insert_positions);
    }

}
