<?php

class NetworkBlocker
{

    /* @var $database Database */
    private $database;

    private $allowed_networks;
    private $allowed_users;

    function __construct(Database $database, Config $allowed_networks, Config $allowed_users)
    {
        $this->database = $database;
        $this->allowed_networks = (array)$allowed_networks;
        $this->allowed_users = (array)$allowed_users;
    }

    function is_blocked($user)
    {
        if ($this->is_special_access_user($user))
            return false;

        return $this->in_allowed_network($user);
    }

    function is_special_access_user($user)
    {
        return in_array($user->facebook_id, $this->allowed_users);
    }

    function in_allowed_network($user)
    {
        $networks_ids = $this->get_network_ids($user);
        $permitted_user_networks = array_intersect($this->allowed_networks, $networks_ids);

        return empty($permitted_user_networks);
    }

    function get_allowed_network_names()
    {
        $names = array();
        foreach ($this->allowed_networks as $id) {
            $name = $this->database->table('networks')->row($id)->name;
            $names[] = $name;
        }
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
