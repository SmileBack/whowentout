<?php

class UpdateFacebookFriendsJob extends Job
{

    /* @var $db Database */
    private $db;

    /* @var $updater FacebookFriendsUpdater */
    private $updater;

    function __construct($options = array())
    {
        parent::__construct($options);

        $this->db = db();
        $this->updater = factory()->build('facebook_friends_updater');
    }

    function run()
    {
        $user_id = $this->options['user_id'];
        $user = $this->db->table('users')->row($user_id);
        $this->updater->update_facebook_friends($user);
    }

}
