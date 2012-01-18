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
        set_time_limit(5 * 60);
        $friends = $this->friend_source->fetch_facebook_friends($user);

        $this->database->begin_transaction();
        // insert all users from $friends who aren't already in the users table
        foreach ($friends as $friend) {
            $friend_row = $this->database->table('users')->create_or_update_row(array(
                'facebook_id' => $friend->id,
                'gender' => $friend->gender,
                'first_name' => $friend->first_name,
                'last_name' => $friend->last_name,
            ));

            $this->database->table('user_friends')->create_or_update_row(array(
                'user_id' => $user->id,
                'friend_id' => $friend_row->id,
            ));

            foreach ($friend->networks as $network) {
                $network_row = $this->database->table('networks')->create_or_update_row(array(
                    'id' => $network->id,
                    'type' => $network->type,
                    'name' => $network->name,
                ));

                $this->database->table('user_networks')->create_or_update_row(array(
                    'user_id' => $friend_row->id,
                    'network_id' => $network_row->id,
                ));
            }
        }
        $this->database->commit_transaction();
    }

}
