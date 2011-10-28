<?php

class ShowLandingPagePlugin extends Plugin
{

    function on_before_controller_request($e)
    {
        if ($e->uri == 'landing')
            return;

        $segments = explode('/', $e->uri);
        if (isset($segments[0]) && $segments[0] == 'job_proxy')
            return;

        if (!college())
            return;
        
        $halloween_launch = new HalloweenLaunch(college()->get_clock());
        if ( ! $halloween_launch->has_launched() )
            redirect('landing');
    }

}
