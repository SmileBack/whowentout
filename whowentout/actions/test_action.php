<?php

class TestAction extends Action
{

    function execute()
    {
//        $env = environment();
        $env = 'whowentout';
        $all_networks = array(
            'localhost' => array('GWU', 'Stanford', 'Georgetown', 'Maryland'),
            'whowasout' => array('GWU', 'Stanford', 'Georgetown', 'Maryland'),
            'whowentout' => array('GWU', 'Georgetown', 'Stanford'),
        );
        $networks = $all_networks[ $env ];

        $networks_sql = "('" . implode("', '", $networks) . "')";
        print $networks_sql;
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
