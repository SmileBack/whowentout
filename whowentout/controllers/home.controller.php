<?php

class Home_Controller extends Controller
{

    function index()
    {
        if (auth()->logged_in())
            redirect('events');

        print r::home();
    }

}
