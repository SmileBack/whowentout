<?php

class LoginAction extends Action
{

    function execute()
    {
        $facebook_login_url = auth()->get_login_url();
        header("Location: $facebook_login_url");
    }

}