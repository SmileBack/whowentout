<?php

class FacebookNetworksUpdater
{

    /* @var $database Database */
    private $database;

    /* @var $profile_source FacebookProfileSource */
    private $profile_source;

    function __construct(Database $database, FacebookProfileSource $profile_source)
    {
        $this->database = $database;
        $this->profile_source = $profile_source;
    }

    /**
     * @param $user_id
     * @param $networks FacebookNetwork[]
     */
    function save_networks($user_id)
    {
        $user = $this->database->table('users')->row($user_id);
        $profile = $this->profile_source->fetch_profile($user->facebook_id);
        $networks = $profile->networks;

        $this->database->begin_transaction();
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
        $this->database->commit_transaction();

    }

}
