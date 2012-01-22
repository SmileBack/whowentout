<?php

class ConfirmDealAction extends Action
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
        $cell_phone_number = $_POST['user']['cell_phone_number'];

        $event_id = $_POST['event_id'];
        $event = $this->db->table('events')->row($event_id);
        $current_user = $this->auth->current_user();

        $current_user->cell_phone_number = $this->format_phone_number($cell_phone_number);
        $current_user->save();

        $has_sent_invites = $this->invite_engine->has_sent_invites($event, $current_user);

        if (flow::get() == 'checkin' && !$has_sent_invites)
            redirect("events/$event->id/invite");
        else
            app()->goto_event($event);
    }

    private function format_phone_number($phone_number)
    {
        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);

        $num_digits = strlen($phone_number);
        if ($num_digits == 7)
            $phone_number = preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $phone_number);
        elseif ($num_digits == 10)
            $phone_number = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '($1) $2-$3', $phone_number);

        return $phone_number;
    }

}
