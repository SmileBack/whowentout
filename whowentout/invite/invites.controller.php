<?php

class Invites_Controller extends Controller
{

    /* @var $invite_engine InviteEngine */
    private $invite_engine;

    function __construct()
    {
        parent::__construct();

        $this->invite_engine = factory()->build('invite_engine');
    }

    function create()
    {
        $event_id = $_POST['event_id'];
        $event = db()->table('events')->row($event_id);

        $sender = auth()->current_user();

        foreach ($_POST['recipients'] as $recipient_id) {
            $receiver = db()->table('users')->row($recipient_id);
            $this->invite_engine->send_invite($event, $sender, $receiver);
        }
    }

}
