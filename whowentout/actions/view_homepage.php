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
            redirect('day');
        }
    }

}
