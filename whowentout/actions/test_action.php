<?php

class TestAction extends Action
{

    function execute()
    {
        $json = to::json(auth()->current_user());
        krumo::dump($json);
    }

    function get_profile_picture_urls($user_ids = array())
    {
        $urls = array();
        foreach ($user_ids as $id) {
            $user = db()->table('users')->row($id);
            $profile_picture = build('profile_picture', $user);
            $url = $profile_picture->url('normal');
            $urls[$id] = $url;
        }
        return $urls;
    }

}
