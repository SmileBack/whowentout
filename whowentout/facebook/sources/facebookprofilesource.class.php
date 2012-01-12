<?php

class FacebookProfileSource
{

    /**
     * @var Facebook
     */
    private $facebook;

    function __construct(Facebook $facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @param FacebookProfile
     */
    function fetch_profile($facebook_id)
    {
        $basic_info = $this->facebook->api('/' . $facebook_id);

        $profile = new FacebookProfile();

        $profile->id = $basic_info['id'];
        $profile->first_name = $basic_info['first_name'];
        $profile->last_name = $basic_info['last_name'];
        $profile->email = isset($basic_info['email']) ? $basic_info['email'] : null;

        $profile->gender = $this->get_gender($basic_info);
        $profile->hometown = isset($basic_info['hometown']) ? $basic_info['hometown']['name'] : null;
        $profile->birthday = $this->get_birthday($basic_info);

        $profile->networks = $this->get_networks($facebook_id);

        return $profile;
    }

    private function get_gender(array $basic_info)
    {
        $map = array('male' => 'M', 'female' => 'F');
        $gender = $basic_info['gender'];
        return isset($map[$gender]) ? $map[$gender] : null;
    }

    /**
     * @return DateTime|null
     */
    private function get_birthday(array $basic_info)
    {
        if (!isset($basic_info['birthday']))
            return null;
        
        return DateTime::createFromFormat('m/d/Y H:i:s', $basic_info['birthday'] . ' 00:00:00', new DateTimeZone('UTC'));
    }

    /**
     * @return FacebookNetwork[]
     */
    private function get_networks($facebook_id)
    {
        $networks = array();

        $result = $this->facebook->api(array(
                                            'method' => 'fql.query',
                                            'query' => "SELECT affiliations FROM user WHERE uid = $facebook_id",
                                       ));

        foreach ($result[0]['affiliations'] as $network_data) {
            $networks[] = new FacebookNetwork($network_data);
        }

        return $networks;
    }
    
}
