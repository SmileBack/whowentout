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
    }

    /**
     * @return FacebookFriend[]
     */
    function fetch_facebook_friends()
    {
        $friends = array();

        $results = $this->facebook->api(array(
            'method' => 'fql.query',
            'query' => "SELECT uid, first_name, last_name, sex, affiliations FROM user
                                                WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = $this->facebook_id)" // AND is_app_user = 1
        ));

        foreach ($results as $result) {
            $friend = new FacebookFriend($result['uid'], $result['first_name'],
                                                         $result['last_name'],
                                                         $result['sex'] == 'male' ? 'M' : 'F');

            foreach ($result['affiliations'] as $affiliation) {
                $friend->networks[] = new FacebookNetwork($affiliation);
            }

            $friends[] = $friend;
        }

        return $friends;
    }

}
