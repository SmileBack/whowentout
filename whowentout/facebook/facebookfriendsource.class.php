<?php

class FacebookFriendSource
{

    /* @var $facebook Facebook */
    private $facebook;

    private $facebook_id;

    function __construct(Facebook $facebook, $facebook_id)
    {
        $this->facebook = $facebook;
        $this->facebook_id = $facebook_id;

        $this->load_data();
    }

    function load_data()
    {
        $results = $this->facebook->api(array(
            'method' => 'fql.query',
            'query' => "SELECT uid, first_name, last_name, sex, affiliations FROM user
                                                WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = $this->facebook_id)" // AND is_app_user = 1
        ));
        krumo::dump($results);
    }

}
