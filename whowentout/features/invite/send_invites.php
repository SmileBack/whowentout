<?php

class SendInvitesAction extends Action
{


    /* @var $db Database */
    private $db;

    /* @var $auth FacebookAuth */
    private $auth;

    /* @var $invite_engine InviteEngine */
    private $invite_engine;

    function __construct()
    {
        $this->db = db();
        $this->auth = build('auth');
        $this->invite_engine = build('invite_engine');
    }

    function execute()
    {
        $event_id = $_POST['event_id'];
        $event = $this->db->table('events')->row($event_id);

        if (isset($_POST['send'])) {
            $sender = $this->auth->current_user();

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
            $receiver = $this->db->table('users')->row($receiver_id);
            $recipients[] = $receiver;
        }
        return $recipients;
    }

}
