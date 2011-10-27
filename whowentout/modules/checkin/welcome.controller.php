<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function index()
    {
        serverchannel()->trigger('job_proxy', 'new_job', array(
                                                'url' => '3453254245245',
                                                         ));
    }

    private function blah()
    {
        print "<h1>woo</h1>";
    }

    function index2()
    {
        $party = XParty::get(32);

        $ven = XUser::get(array('first_name' => 'Venkat'));
        $dan = XUser::get(array('last_name' => 'Berenholtz'));

        $maggie = user(96);
        $claire = user(82);
        $jenny = user(108);
        $allie = user(184);
    }

}
