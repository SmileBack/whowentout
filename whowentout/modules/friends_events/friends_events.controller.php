<?php

class Friends_Events extends MY_Controller
{

    function run()
    {
        $this->require_login();

        if (!current_user()->is_admin())
            show_error('Only for admin');

        krumo::dump($this->get_website_gwu_network_facebook_ids());
    }

    private function get_website_facebook_ids()
    {
        $rows = $this->db->select('facebook_id, facebook_access_token')
                ->from('users')
                ->where('facebook_access_token IS NOT NULL')->get()->result();

        $facebook_ids = array();
        foreach ($rows as $row) {
            $facebook_ids[] = $row->facebook_id;
        }
        return $facebook_ids;
    }

    private function get_website_gwu_network_facebook_ids()
    {
        $rows = $this->db->select('facebook_id, facebook_access_token')
                ->from('users')
                ->where('facebook_access_token IS NOT NULL')->get()->result();
        
        $website_gwu_network_facebook_ids = array();
        foreach ($rows as $row) {
            fb()->setAccessToken($row->facebook_access_token);
            $facebook_friend_ids = $this->get_user_gwu_facebook_friends($row->facebook_id);
            $website_gwu_network_facebook_ids = array_merge($website_gwu_network_facebook_ids, $facebook_friend_ids);
        }

        return $website_gwu_network_facebook_ids;
    }


    private function get_user_gwu_facebook_friends($user_facebook_id)
    {
        $result = fb()->api(array(
                                 'method' => 'fql.query',
                                 'query' => "SELECT uid, name FROM user WHERE uid IN (SELECT uid1 FROM friend WHERE uid2=$user_facebook_id) AND 'GWU' IN affiliations",
                            ));
        $facebook_friend_ids = array();
        foreach ($result as $k => $row) {
            $facebook_friend_ids[] = $row['uid'];
        }
        return $facebook_friend_ids;
    }

}
