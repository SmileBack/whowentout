<?php

class Admin_Controller extends Controller
{

    function index()
    {
        auth()->require_admin();
        print r::page(array(
                           'content' => r::admin_index(),
                      ));
    }

}
