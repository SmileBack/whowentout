<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function index()
    {
        $ven = user(array('first_name' => 'Venkat'));
        $ven->recent_parties();
    }

    function blah()
    {
        print "<h1>woo</h1>";
    }

    function index2()
    {
        $party = party(32);

        $ven = user(array('first_name' => 'Venkat'));
        $dan = user(array('last_name' => 'Berenholtz'));

        $maggie = user(96);
        $claire = user(82);
        $jenny = user(108);
        $allie = user(184);
    }

}
