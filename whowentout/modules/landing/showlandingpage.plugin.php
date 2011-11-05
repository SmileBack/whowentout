<?php

class ShowLandingPagePlugin extends Plugin
{

    private $environments = array();

    function show_landing()
    {
        return in_array(ENVIRONMENT, $this->environments);
    }

    function on_before_controller_request($e)
    {
        if (!$this->show_landing())
            return FALSE;
        
        if ($e->uri == 'landing')
            return;

        $segments = explode('/', $e->uri);
        if (isset($segments[0]) && $segments[0] == 'job_proxy')
            return;

        if (!college())
            return;

        redirect('landing');
    }
    
}
