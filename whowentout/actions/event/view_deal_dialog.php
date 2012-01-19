<?php

class ViewDealDialogAction extends Action
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

    function execute($event_id)
    {
        $event = $this->db->table('events')->row($event_id);
        $current_user = $this->auth->current_user();
        $has_invited = $this->invite_engine->has_sent_invites($event, $current_user);

        PageFlow::start(new DealPageFlow($event->id));

        print r::deal_popup(array(
            'user' => $current_user,
            'event' => $event,
            'has_invited' => $has_invited,
        ));
    }

}
