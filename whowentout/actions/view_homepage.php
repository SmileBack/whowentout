<?php

class ViewHomepage extends Action
{

    /* @var $network_blocker NetworkBlocker */
    private $blocker;

    function __construct()
    {
        $this->blocker = build('network_blocker');
    }

    function execute()
    {
        if (auth()->logged_in()) {
            /* @var $updater FacebookNetworksUpdater */
            $updater = build('facebook_networks_updater');
            $updater->update_networks(auth()->current_user()->id);
        }

        if (!auth()->logged_in()) {
            print r::home();
        }
        elseif ($this->blocker->is_blocked(auth()->current_user())) {
            js()->whowentout->showNetworkRequiredDialog();
            print r::home();
        }
        else {
            redirect('today');
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