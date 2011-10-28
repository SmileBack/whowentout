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
        return getenv('countdown_target')
                ? strtotime(getenv('countdown_target'))
                : strtotime('October 27, 2011 08:11:00 PM');
    }

}
