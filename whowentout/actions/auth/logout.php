<?php

class LogoutAction extends Action
{
    function execute()
    {
        auth()->logout();
        redirect('/');
    }
}
