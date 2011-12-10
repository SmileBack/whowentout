<?php

class FacebookProfileSource
{

    /**
     * @var Facebook
     */
    private $facebook;

    private $facebook_id;

    private $basic_info;

    function __construct(Facebook $facebook, $facebook_id)
    {
        $this->facebook = $facebook;
        $this->facebook_id = $facebook_id;

        $this->load_data();
        $this->get_networks();
    }

    function load_data()
    {
        $this->basic_info = $this->facebook->api('/' . $this->facebook_id);
    }

    function get_facebook_id()
    {
        return $this->basic_info['id'];
    }

    function get_first_name()
    {
        return $this->basic_info['first_name'];
    }

    function get_last_name()
    {
        return $this->basic_info['last_name'];
    }

    function get_email()
    {
        return $this->basic_info['email'];
    }

    function get_gender()
    {
        $map = array('male' => 'M', 'female' => 'F');
        $gender = $this->basic_info['gender'];
        return isset($map[$gender]) ? $map[$gender] : null;
    }

    function get_hometown()
    {
        return $this->basic_info['hometown']['name'];
    }

    function get_location()
    {
        return $this->basic_info['location']['name'];
    }
    
    /**
     * @return DateTime|null
     */
    function get_birthday()
    {
        if (!isset($this->basic_info['birthday']))
            return null;
        
        return DateTime::createFromFormat('m/d/Y H:i:s', $this->basic_info['birthday'] . ' 00:00:00', new DateTimeZone('UTC'));
    }

    /**
     * @return FacebookNetwork[]
     */
    function get_networks()
    {
        $networks = array();

        $result = $this->facebook->api(array(
                                            'method' => 'fql.query',
                                            'query' => "SELECT affiliations FROM user WHERE uid = $this->facebook_id",
                                       ));

        foreach ($result[0]['affiliations'] as $network_data) {
            $networks[] = new FacebookNetwork($network_data);
        }

        return $networks;
    }

}
