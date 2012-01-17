<?php

class UpdateFacebookNetworksJob extends Job
{

    /* @var $db Database */
    private $db;

    /* @var $updater FacebookNetworksUpdater */
    private $updater;

    function __construct($options = array())
    {
        parent::__construct($options);

        $this->db = db();
        $this->updater = build('facebook_networks_updater');
    }

    function run()
    {
        $user_id = $this->options['user_id'];
        $this->updater->save_networks($user_id);
    }

}
