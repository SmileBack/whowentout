<?php

class LoginAsAction extends Action
{
    function execute($user_id)
    {
        if (!$user_id) {
            print r::login_as();
        }
        else {
            auth()->login_as($user_id);
            redirect('/');
        }
    }
}
