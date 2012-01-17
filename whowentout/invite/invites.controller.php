<?php

class Invites_Controller extends Controller
{

    /* @var $invite_engine InviteEngine */
    private $invite_engine;

    function __construct()
    {
        parent::__construct();

        $this->invite_engine = build('invite_engine');
    }

    function create()
    {
        $event_id = $_POST['event_id'];
        $event = db()->table('events')->row($event_id);

        if (isset($_POST['send'])) {
            $sender = auth()->current_user();

            foreach ($this->get_recipients() as $receiver) {
                $this->invite_engine->send_invite($event, $sender, $receiver);
                flash::message('Sent invites');
            }
        }

        app()->goto_event($event);
    }

    private function get_recipients()
    {
        $recipients = array();
        foreach ($_POST['recipients'] as $receiver_id) {
            $receiver = db()->table('users')->row($receiver_id);
            $recipients[] = $receiver;
        }
        return $recipients;
    }

}
