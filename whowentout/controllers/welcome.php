<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{


    function index()
    {
        $this->load->library('chat');

        $party = party(32);

        $ven = user(array('first_name' => 'Venkat'));
        $dan = user(array('last_name' => 'Berenholtz'));

        $remi = user(97);
        $maggie = user(96);
        $claire = user(82);
        $jenny = user(108);

        $js = array('lib/jquery.js',
                    'lib/underscore.js',
                    'lib/jquery.jstorage.js',
                    'lib/jquery.idle-timer.js',
                    'lib/jquery.form.js',
                    'lib/jquery.entwine.js',
                    'lib/jquery.class.js',
                    'lib/jquery.ext.js');

        $dt = new DateTime(NULL, new DateTimeZone('UTC'));
        var_dump($dt);
    }

}
