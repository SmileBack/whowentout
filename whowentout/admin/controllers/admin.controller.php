<?php

class Admin_Controller extends Controller
{

    function index()
    {
        print r::page(array(
                           'content' => r::admin_index(),
                      ));
    }

}
