<?php

class Job_Proxy extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function dandorroyven()
    {
        f()->window_settings['job_proxy']['channel'] = 'job_proxy_' . ENVIRONMENT;
        f()->window_settings['job_proxy']['presence_channel'] = 'presence-job_proxy_' . ENVIRONMENT;
        print r('job_proxy_page');
    }

    function pusherauth()
    {
        require_once APPPATH . 'third_party/pusher.php';
        $this->load->config('pusher');

        $channel_name = post('channel_name');
        $socket_id = post('socket_id');
        $user_can_access_channel = TRUE;

        if (!$user_can_access_channel) {
            $this->json_failure("You don't have permission to access this channel.");
        }

        $custom_data = array(
            'user_id' => time(),
        );

        $pusher = new Pusher($this->config->item('pusher_app_key'),
            $this->config->item('pusher_app_secret'),
            $this->config->item('pusher_app_id'));

        print $pusher->socket_auth($channel_name, $socket_id, json_encode($custom_data));
    }

}
