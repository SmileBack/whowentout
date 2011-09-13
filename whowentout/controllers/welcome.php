<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Welcome extends MY_Controller
{

    function index()
    {
    }

    function index2()
    {
        $this->load->library('chat');

        $party = party(11);

        $ven = user(array('first_name' => 'Venkat'));
        $dan = user(array('last_name' => 'Berenholtz'));
        
        $remi = user(97);
        $maggie = user(96);
        $claire = user(82);
        $jenny = user(108);
        
        $response = fb()->api(array(
                                   'method' => 'fql.query',
                                   'query' => "SELECT affiliations FROM user WHERE uid = 776200121",
                              ));

        print '<pre>';
        print_r($response);
        print '</pre>';
    }

}
