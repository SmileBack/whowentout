<?php

class TestAction extends Action
{

    function execute()
    {
        $user_ids = range(8250, 8270);
        $urls = $this->get_profile_picture_urls($user_ids);

        krumo::dump($urls);
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
