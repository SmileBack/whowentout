<?php

class NetworkBlocker
{

    /* @var $database Database */
    private $database;

    private $allowed_networks;

    function __construct(Database $database, $allowed_networks)
    {
        $this->database = $database;
        $this->allowed_networks = $allowed_networks;
    }

    function is_blocked($user)
    {
        $networks_ids = $this->get_network_ids($user);

        krumo::dump($this->allowed_networks);
        krumo::dump($networks_ids);

        $permitted_user_networks = array_intersect($this->allowed_networks, $networks_ids);

        krumo::dump($permitted_user_networks);

        return empty($permitted_user_networks);
    }

    function get_allowed_network_names()
    {
        $names = array();
        foreach ($this->allowed_networks as $id) {
            $name = $this->database->table('networks')->row($id)->name;
            $names[] = $name;
        }
        krumo::dump($names);
        return $names;
    }

    private function get_network_ids($user)
    {
        $network_ids = array();
        foreach ($user->networks as $network)
            $network_ids[] = $network->id;

        return $network_ids;
    }

}
