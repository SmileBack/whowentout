<?php

class SendEntourageRequestsAction extends Action
{
    function execute()
    {
        /* @var $entourage_engine EntourageEngine */
        $entourage_engine = build('entourage_engine');

        $current_user = auth()->current_user();
        $recipients = $_POST['recipients'];

        $n = 0;

        foreach ($recipients as $recipient_id) {
            $recipient = db()->table('users')->row($recipient_id);
            $entourage_engine->send_request($current_user, $recipient);
            $n++;
        }

        $entourage_requests = Inflect::pluralize_if($n, 'entourage request');

        flash::message("Sent $entourage_requests.");

        redirect('entourage');
    }

}
