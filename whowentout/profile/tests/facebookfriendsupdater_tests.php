<?php

class FacebookFriendsUpdater_Tests extends PHPUnit_Framework_TestCase
{

    /* @var $db Database */
    private $db;

    function setUp()
    {
        $factory = factory('facebookfriendsupdater_tests');
        $this->db = $factory->build('test_database');
    }

    function test_friends_are_updated()
    {

    }

}