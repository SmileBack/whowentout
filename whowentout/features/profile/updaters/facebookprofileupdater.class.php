<?php

class FacebookProfileUpdater
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
    function update_profile($user_id)
    {
        $user = $this->database->table('users')->row($user_id);

        $profile = $this->profile_source->fetch_profile($user->facebook_id);

        $user->first_name = $profile->first_name;
        $user->last_name = $profile->last_name;
        $user->gender = $profile->gender;
        $user->email = $profile->email;
        $user->hometown = $profile->hometown;
        $user->facebook_profile_last_update = app()->clock()->get_time();

        $user->save();
    }

}
