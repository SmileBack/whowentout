<?php

class Presence
{

    function flag_online($token)
    {

    }

    function flag_offline($token)
    {
        
    }

    function give_token($user_id)
    {
        
    }

    function token()
    {
        return hash('sha256', uniqid(mt_rand(), true));
    }

    function __install()
    {
        
    }

    function __uninstall()
    {
        
    }

}
