<?php

class Landing extends MY_Controller
{

    function index()
    {
        print r('landing_page', array(
                                     'countdown_target' => $this->get_countdown_target_timestamp(),
                                ));
    }

    function get_countdown_target_timestamp()
    {
        $launch = new HalloweenLaunch(college()->get_clock());
        return $launch->get_launch_date()->getTimestamp();
    }



}
