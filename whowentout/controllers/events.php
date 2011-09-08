<?php

class Events extends MY_Controller
{

    function version()
    {
        $this->json(array(
                        'version' => $this->event->version(),
                    ));
    }

    function fetch($channel, $version)
    {
        //TODO: check for permissions
        $this->json(array(
                        'version' => $this->event->version(),
                        'events' => $this->event->fetch($channel, $version),
                    ));
    }
    
}
