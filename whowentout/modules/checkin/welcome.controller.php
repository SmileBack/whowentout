<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function index()
    {
        krumo::dump(current_user()->has_facebook_permission('offline_access'));
    }

    function test_email()
    {
        job_call_async('send_email', current_user(), 'hello ' . rand(1, 100), 'hello here is a random number ' . rand(1, 100));
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
