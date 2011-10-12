<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{


    function index()
    {
        $party = party(32);

        $ven = user(array('first_name' => 'Venkat'));
        $dan = user(array('last_name' => 'Berenholtz'));

        $remi = user(97);
        $maggie = user(96);
        $claire = user(82);
        $jenny = user(108);
        $allie = user(184);

        $this->load->library('presence');
        var_dump( $this->presence->is_online($ven->id) );
        var_dump( $ven->is_online_to($allie) );
    }

}
