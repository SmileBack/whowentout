<?php

class Events extends MY_Controller
{

    function version()
    {
        print json_encode(array(
                              'version' => $this->event->version(),
                          ));
        exit;
    }

    function fetch($source, $version)
    {
        //TODO: check for permissions
        print json_encode(array(
                              'events' => $this->event->fetch($source, $version),
                          ));
        exit;
    }
    
}