<?php

class Facebook_Profile_Source_Tests extends TestGroup
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


    function setup()
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

        $this->assert_equal($first_name, 'Venkat');
        $this->assert_equal($last_name, 'Dinavahi');
    }

    function test_get_birthday()
    {
        $birthday = $this->profile_source->get_birthday();
        $this->assert_equal($birthday->format('Y-m-d'), '1988-10-06');
    }

    function test_get_gender()
    {
        $gender = $this->profile_source->get_gender();
        $this->assert_equal($gender, 'M');
    }

    function test_get_hometown()
    {
        $hometown = $this->profile_source->get_hometwon();
        $this->assert_equal($hometown, 'Severna Park, Maryland');
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

        $this->assert_equal(count($intersect), 2, 'maryland and stanford networks are present');
    }

}
