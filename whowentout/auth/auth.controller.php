<?php

class Auth_Controller extends Controller
{
    
    function login()
    {
        $facebook_login_url = auth()->get_login_url();
        header("Location: $facebook_login_url");
    }

    function login_as($facebook_user_id)
    {
        auth()->login_as($facebook_user_id);

        redirect('events');
    }

    function complete()
    {
        if (auth()->logged_in())
            auth()->create_user();

        redirect('events');
    }

    function logout()
    {
        auth()->logout();

        redirect('');
    }

}
