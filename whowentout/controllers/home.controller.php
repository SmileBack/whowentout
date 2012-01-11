<?php

class Home_Controller extends Controller
{

    /* @var $network_blocker NetworkBlocker */
    private $blocker;

    function __construct()
    {
        $this->blocker = factory()->build('network_blocker');
    }

    function index()
    {
        if (!auth()->logged_in()) {
            print r::home();
        }
        elseif ($this->blocker->is_blocked(auth()->current_user())) {
            js()->whowentout->showNetworkRequiredDialog();
            print r::home();
        }
        else {
            redirect('events');
        }
    }

    function network_required()
    {
        $allowed_networks = $this->blocker->get_allowed_network_names();
        $add_network_link = 'https://www.facebook.com/settings?tab=account&section=networks';
        $message = '<p>You must be in the Facebook network for ' . conjunct($allowed_networks, 'or') . ' to use this website.</p>';
        $message .= sprintf('<p>If you go to one of these colleges, <a href="%s" target="_blank">click here</a> to add yourself to the network.</p>', $add_network_link);
        print $message;
    }

}
