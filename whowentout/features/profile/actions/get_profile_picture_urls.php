<?php

class GetProfilePictureUrlsAction extends Action
{

    /**
     * @var Database
     */
    private $database;

    /**
     * @var ProfilePictureFactory
     */
    private $profile_picture_factory;

    function __construct()
    {
        $this->database = db();
        $this->profile_picture_factory = build('profile_picture_factory');
    }

    function execute()
    {
        $user_ids = $_GET['user_ids'];

        $response = array('success' => true);
        $response['urls'] = $this->get_profile_picture_urls($user_ids);

        print json_encode($response);exit;
    }

    function get_profile_picture_urls($user_ids = array())
    {
        $urls = array();
        foreach ($user_ids as $id) {
            $user = $this->database->table('users')->row($id);

            if (!$user)
                continue;

            $profile_picture = $this->profile_picture_factory->build($user);
            $url = $profile_picture->url('normal');
            $urls[$id] = $url;
        }
        return $urls;
    }

}

