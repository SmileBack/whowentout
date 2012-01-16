<?php

class Auth_Controller extends Controller
{
    
    function login()
    {
        $facebook_login_url = auth()->get_login_url();
        header("Location: $facebook_login_url");
    }

    function login_as($facebook_user_id = null)
    {
        if (!$facebook_user_id) {
            print r::login_as();
        }
        else {
            auth()->login_as($facebook_user_id);
            redirect('events');
        }
    }

    function complete()
    {
        if (auth()->logged_in())
            auth()->create_user();

        app()->trigger('login', array(
            'user' => auth()->current_user(),
        ));

        redirect('events');
    }

    function logout()
    {
        auth()->logout();

        redirect('');
    }

}
