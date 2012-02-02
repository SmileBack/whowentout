<?php

class AcceptEntourageRequestAction extends Action
{


    /* @var $engine EntourageEngine */
    private $engine;

    function __construct()
    {
        $this->engine = build('entourage_engine');
    }

    function execute()
    {
        /* @var $engine EntourageEngine */
        $this->engine = build('entourage_engine');

        $current_user = auth()->current_user();

        $request_id = $_POST['request_id'];
        $op = $_POST['op'];

        $request = $this->engine->get_request($request_id);

        if ($request->receiver != auth()->current_user())
            throw new Exception("Invalid operation.");

        if ($op == 'accept') {
            $this->engine->accept_request($request);
            flash::message("Accepted {$request->sender->first_name} {$request->sender->last_name} into your entourage.");
        }
        elseif ($op == 'ignore') {
            $this->engine->ignore_request($request);
            flash::message("Ignored {$request->sender->first_name} {$request->sender->last_name}'s request to be in your entourage.");
        }

        redirect("profile/$current_user->id");
    }

}
