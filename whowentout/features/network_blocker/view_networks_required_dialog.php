<?php

class ViewNetworksRequiredDialog extends Action
{

    /* @var $network_blocker NetworkBlocker */
    private $blocker;

    function __construct()
    {
        $this->blocker = build('network_blocker');
    }

    function execute()
    {
        print r::required_networks(array(
            'networks' => $this->blocker->get_allowed_network_names(),
        ));
    }

}
