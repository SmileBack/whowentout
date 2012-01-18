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
            redirect('/');
        }
    }

    function complete()
    {
        /* @var $facebook Facebook */
        $facebook = build('facebook');

        if (auth()->logged_in())
            auth()->create_user();

        app()->trigger('login', array(
            'user' => auth()->current_user(),
        ));

        auth()->current_user()->last_login = app()->clock()->get_time();
        auth()->current_user()->facebook_access_token = $facebook->getAccessToken();
        auth()->current_user()->save();

        redirect('/');
    }

    function logout()
    {
        auth()->logout();

        redirect('/');
    }

}
