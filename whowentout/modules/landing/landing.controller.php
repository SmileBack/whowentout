<?php

class Landing extends MY_Controller
{

    function index()
    {
        print r('landing_page', array(
                                  'countdown_target' => '',
                                ));
    }
    
}
