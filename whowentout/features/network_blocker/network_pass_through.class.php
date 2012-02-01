<?php

class NetworkPassThrough
{
    function is_blocked()
    {
        return false;
    }

    function get_allowed_network_names()
    {
        return array();
    }
}
