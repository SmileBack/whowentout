<?php

class FacebookProfile
{
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $gender;
    public $hometown;
    public $location;

    /* @var $birthday DateTime */
    public $birthday;

    /* @var $networks FacebookNetwork[] */
    public $networks = array();
}
