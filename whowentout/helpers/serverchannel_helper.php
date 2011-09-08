<?php

/**
 * @return ServerChannel
 */
function serverchannel()
{
    static $serverchannel = NULL;
    if (!$serverchannel) {
        $ci =& get_instance();
        $ci->load->library('serverchannel');
        $serverchannel = $ci->serverchannel;
    }
    
    return $serverchannel;
}
