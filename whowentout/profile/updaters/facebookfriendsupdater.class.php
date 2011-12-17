<?php

class FacebookFriendsUpdater
{

    /* @var $database Database */
    private $database;

    /* @var $profile_source FacebookProfileSource */
    private $friend_source;

    function __construct(Database $database, FacebookFriendSource $friend_source)
    {
        $this->database = $database;
        $this->friend_source = $friend_source;
    }

    /**
     * @param $user
     */
    function update_facebook_friends($user)
    {
        $friends = $this->friend_source->fetch_facebook_friends();

        // insert all users from $friends who aren't already in the users table

        // delete all entries from user_friends where user_id = $user->id

        // insert all entries into user_friends
    }

}