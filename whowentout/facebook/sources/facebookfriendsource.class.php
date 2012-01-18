<?php

class FacebookFriendSource
{

    /* @var $facebook Facebook */
    private $facebook;

    function __construct(Facebook $facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return FacebookFriend[]
     */
    function fetch_facebook_friends($user)
    {
        $friends = array();

        $prev_access_token = $this->facebook->getAccessToken();
        $this->facebook->setAccessToken($user->facebook_access_token);

        $results = $this->facebook->api(array(
            'method' => 'fql.query',
            'query' => "SELECT uid, first_name, last_name, sex, affiliations FROM user
                                                WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = $user->facebook_id)" // AND is_app_user = 1
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

        $this->facebook->setAccessToken($prev_access_token);

        return $friends;
    }

}
