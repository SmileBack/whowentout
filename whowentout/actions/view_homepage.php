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
            if (isset($_GET['invite_id']))
                $_SESSION['invite_id'] = $_GET['invite_id'];

            print r::home();
        }
        elseif ($this->blocker->is_blocked(auth()->current_user())) {
            js()->whowentout->showNetworkRequiredDialog();
            print r::home();
        }
        elseif (isset($_SESSION['invite_id'])) {
            $invite_id = $_SESSION['invite_id'];
            unset($_SESSION['invite_id']);
            $invite = db()->table('invites')->row($invite_id);
            app()->goto_event($invite->event);
        }
        else {
            redirect('day');
        }
    }

}
