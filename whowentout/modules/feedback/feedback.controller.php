<?php

class Feedback extends MY_Controller
{

    function send()
    {
        $this->require_login();

        $feedback = post('feedback');
        $user_id = current_user()->id;
        $user_name = current_user()->full_name;

        $subject = "[$user_id] $user_name";
        $body = $feedback;

        job_call_async('send_email', 'feedback@whowentout.com', $subject, $body);

        $this->json_success();
    }

}
