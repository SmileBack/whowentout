<?php

class ShowLandingPagePlugin extends Plugin
{

    function on_before_controller_request($e)
    {
        if ($e->uri == 'landing')
            return;

        if (!college())
            return;
        
        $halloween_launch = new HalloweenLaunch(college()->get_clock());
        if ( ! $halloween_launch->has_launched() )
            redirect('landing');
    }

}
