<?php

class FacebookFriend
{

    /* @var $id int */
    public $id;

    /* @var $first_name string */
    public $first_name;

    /* @var $last_name string */
    public $last_name;

    /* @var $gender string M|F */
    public $gender;

    /* @var $networks FacebookNetwork[] */
    public $networks = array();

    /**
     * @param $id int
     * @param $first_name string
     * @param $last_name string
     * @param $gender string M|F
     * @param array $networks FacebookNetwork[]
     */
    function __construct($id, $first_name, $last_name, $gender, $networks = array())
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->gender = $gender;
        $this->networks = $networks;
    }

}
