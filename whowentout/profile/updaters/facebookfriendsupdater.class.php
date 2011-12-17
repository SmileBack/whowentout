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
        foreach ($friends as $friend) {
            $this->database->table('users')->create_or_update_row(array(
                'facebook_id' => $friend->id,
                'gender' => $friend->gender,
                'first_name' => $friend->first_name,
                'last_name' => $friend->last_name,
            ));
            krumo::dump($friend->networks);
            foreach ($friend->networks as $network) {
                $this->database->table('networks')->create_or_update_row(array(
                    'id' => $network->id,
                    'type' => $network->type,
                    'name' => $network->name,
                ));
            }
        }

        // delete all entries from user_friends where user_id = $user->id

        // insert all entries into user_friends
    }

}
