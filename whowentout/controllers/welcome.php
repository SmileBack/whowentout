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

        $smile_a_id = 7;
        $smile_b_id = 8;

        var_dump( $this->smiles_in_previous_match(array(6, 8)) );
    }

}
