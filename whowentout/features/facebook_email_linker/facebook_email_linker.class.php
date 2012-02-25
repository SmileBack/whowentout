<?php

class FacebookEmailLinker
{

    function __construct($url)
    {
        $this->curl = new Curl();
        $this->url = $url;
    }

    function get_match($network, $full_name, $facebook_id)
    {
        $response = $this->curl->get($this->url, array(
            'network' => $network,
            'name' => $full_name,
            'facebook_id' => $facebook_id,
        ));
        $response = json_decode($response->body);
        return $response;
    }

}
