<?php

class NetworkBlockerPlugin extends Plugin
{

    /* @var $networks_updater FacebookNetworksUpdater */
    private $networks_updater;

    /* @var $blocker NetworkBlocker */
    private $blocker;

    function on_login($e)
    {
        $this->networks_updater = build('facebook_networks_updater');
        $this->blocker = build('network_blocker');

        $user = $e->user;

        $this->networks_updater->save_networks($user->id);

        if ($this->blocker->is_blocked($user))
            redirect('/');
    }

}
