<?php

class UserLogEntry extends MY_Controller
{

    function create()
    {
        $this->require_login();

        $action_name = 'browser_' . post('action_name');
        $action_data = post('action_data');
        
        $logger = new UserEventLogger();
        $logger->log(current_user(), college()->get_time(), $action_name, $action_data);

        $this->json_success();
    }

}
