<?php

class ConfirmCheckinExplanationAction extends Action
{

    /* @var $invite_engine InviteEngine */
    private $invite_engine;

    function __construct()
    {
        $this->current_user = auth()->current_user();
        $this->invite_engine = build('invite_engine');
    }

    function execute()
    {
        $event = db()->table('events')->row($_POST['event_id']);
        $has_sent_invites = $this->invite_engine->has_sent_invites($event, $this->current_user);

        if ($event->deal != null) {
            redirect("events/$event->id/deal");
        }
        elseif (!$has_sent_invites) {
            redirect("events/$event->id/invite");
        }
        else {
            app()->goto_event($event);
        }
    }

}
