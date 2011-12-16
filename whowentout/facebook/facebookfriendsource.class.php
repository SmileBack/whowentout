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

    function load_data()
    {
        
    }

}