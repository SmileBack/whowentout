<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function index()
    {
        $checkin_state = new UserCheckinState(current_user());
        krumo::dump($checkin_state->get_checked_in_party());
    }

    private function blah()
    {
        print "<h1>woo</h1>";
    }

    function index2()
    {
        $party = XParty::get(32);

        $ven = user(array('first_name' => 'Venkat'));
        $dan = user(array('last_name' => 'Berenholtz'));

        $maggie = user(96);
        $claire = user(82);
        $jenny = user(108);
        $allie = user(184);
    }

}
