<?php

class FacebookNetworksUpdater
{

    private $database;

    function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param $user_id
     * @param $networks FacebookNetwork[]
     */
    function save_networks($user_id, $networks)
    {
        $this->database->execute('DELETE FROM user_networks WHERE user_id = :id', array(
            'id' => $user_id,
        ));

        foreach ($networks as $network) {
            $this->database->table('networks')->create_or_update_row(array(
                'id' => $network->id,
                'type' => $network->type,
                'name' => $network->name,
            ));

            $this->database->table('user_networks')->create_or_update_row(array(
                'user_id' => $user_id,
                'network_id' => $network->id,
            ));
        }

    }

}
