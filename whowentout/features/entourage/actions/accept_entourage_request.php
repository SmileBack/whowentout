<?php

class AcceptEntourageRequestAction extends Action
{

    function execute()
    {
        /* @var $engine EntourageEngine */
        $engine = build('entourage_engine');

        $current_user = auth()->current_user();

        $request_id = $_POST['request_id'];
        $request = $engine->get_request($request_id);
        $sender = $request->sender;

        flash::message("Accepted {$sender->first_name} {$sender->last_name} into your entourage.");

        redirect("profile/$current_user->id");
    }



}
