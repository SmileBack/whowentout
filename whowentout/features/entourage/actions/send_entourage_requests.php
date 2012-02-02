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

        $requests = Inflect::pluralize_if($n, 'request');

        flash::message("Sent $n entourage $requests.");

        redirect(app()->profile_link($current_user));
    }
}
