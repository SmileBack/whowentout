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

        $matches = $ven->matches(24);
        $match = $matches[0];
        var_dump($match->other_user);
//        $jenny->smile_at($ven, $party);
//        $ven->smile_at($jenny, $party);
//        var_dump($ven->matches($party));
    }

}
