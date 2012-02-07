<?php

class NetworkSource
{

    private $database;

    function __construct(Database $database)
    {
        $this->database = $database;
    }

    function get_network_names($user_id)
    {
        $user = $this->database->table('users')->row($user_id);

        if ($user->college_networks == null) {
            $names = array();
            $networks = $this->database->table('users')->where('id', $user_id)
                    ->networks->where('type', 'college');

            foreach ($networks as $network)
                $names[] = $network->name;

            $user->college_networks = empty($names) ? '-' : implode(', ', $names);
            $user->save();
        }

        return $user->college_networks == '-'
                ? array()
                : preg_split('/\s*,\s*/', $user->college_networks);
    }

}
