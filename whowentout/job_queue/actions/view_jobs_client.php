<?php

class ViewJobsClient extends Action
{
    function execute()
    {
        js()->pusher_key = $this->get_pusher_key();
        print r::job_client();
    }

    private function get_pusher_key()
    {
        if (environment() == 'localhost')
            return '805af8a6919abc9fb047';
        elseif (environment() == 'whowasout')
            return 'cc920ca581a4b74b17dd';
        elseif (environment() == 'whowentout')
            return '8d634f5c91dded3c5ba9';
        else
            return null;
    }

}
