<?php

class LoginAsAction extends Action
{
    function execute($facebook_user_id)
    {
        if (!$facebook_user_id) {
            print r::login_as();
        }
        else {
            auth()->login_as($facebook_user_id);
            redirect('/');
        }
    }
}
