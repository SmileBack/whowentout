<?php

class Facebook_Profile_Source_Tests extends PHPUnit_Framework_TestCase
{

    /**
     * @var Facebook
     */
    private $facebook;

    /**
     * @var FacebookProfileSource
     */
    private $profile_source;

    private $venkat_facebook_id = '776200121';


    function setUp()
    {
        $factory = factory('profile_source_tests', array(
                                                          'facebook' => array(
                                                              'type' => 'Facebook',
                                                              'config' => array(
                                                                  'appId' => '161054327279516',
                                                                  'secret' => '8b1446580556993a34880a831ee36856',
                                                              ),
                                                          ),
                                                     ));

        $this->facebook = $factory->build('facebook');
        $this->profile_source = new FacebookProfileSource($this->facebook, $this->venkat_facebook_id);
    }

    function test_get_name()
    {
        $first_name = $this->profile_source->get_first_name();
        $last_name = $this->profile_source->get_last_name();

        $this->assertEquals($first_name, 'Venkat');
        $this->assertEquals($last_name, 'Dinavahi');
    }

    function test_get_birthday()
    {
        $birthday = $this->profile_source->get_birthday();
        $this->assertEquals($birthday->format('Y-m-d'), '1988-10-06');
    }

    function test_get_gender()
    {
        $gender = $this->profile_source->get_gender();
        $this->assertEquals($gender, 'M');
    }
    
    function test_get_hometown()
    {
        $hometown = $this->profile_source->get_hometown();
        $this->assertEquals($hometown, 'Severna Park, Maryland');
    }

    function test_get_networks()
    {
        $expected_network_ids = array('16777219', '16777274');
        $actual_network_ids = array();

        /* @var $networks FacebookNetwork[] */
        $networks = $this->profile_source->get_networks();
        foreach ($networks as $network) {
            $actual_network_ids[] = $network->id;
        }

        $intersect = array_intersect($expected_network_ids, $actual_network_ids);

        $this->assertEquals(count($intersect), 2, 'maryland and stanford networks are present');
    }

}
