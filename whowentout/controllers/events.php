<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Events extends MY_Controller
{

    function version($channel)
    {
        $this->json(array(
                        'version' => $this->event->version($channel),
                    ));
    }

    function fetch($channel, $version)
    {
        sleep(random(1, 10));
        //TODO: check for channel permissions
        $this->json(array(
                        'version' => $this->event->version($channel),
                        'events' => $this->event->fetch($channel, $version),
                    ));
    }
    
}
