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

        $this->load->library('asset');

        $this->asset->load(array(
                                'whowentout.application.js',
                                'widgets/jquery.autocomplete.js',
                                'widgets/jquery.dialog.js',
                                'widgets/jquery.notifications.js',
                                'widgets/chatbar.js',

                                'core.js',
                                'time.js',

                                'pages/editinfo.js',
                                'pages/home.js',
                                'pages/dashboard.js',
                                'pages/gallery.js',
                                'pages/editinfo.js',
                                'pages/friends.js',

                                'script.js',

                                'lib/jsaction.js',
                                'actions.js',
                           ));

        print $this->asset->js();
    }

}
