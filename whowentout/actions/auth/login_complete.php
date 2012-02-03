<?php

class LoginComplete extends Action
{

    function execute()
    {
        /* @var $facebook Facebook */
        $facebook = build('facebook');

        if (auth()->logged_in())
            auth()->create_user();

        auth()->current_user()->last_login = app()->clock()->get_time();
        auth()->current_user()->facebook_access_token = $facebook->getAccessToken();
        auth()->current_user()->save();

        app()->trigger('login', array(
            'user' => auth()->current_user(),
        ));

        redirect('/');
    }

}
