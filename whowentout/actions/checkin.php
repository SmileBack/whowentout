<?php

class CheckinAction extends Action
{

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    /* @var $invite_engine InviteEngine */
    private $invite_engine;

    function __construct()
    {
        $this->checkin_engine = build('checkin_engine');
        $this->invite_engine = build('invite_engine');
    }

    function execute()
    {
        $current_user = auth()->current_user();

        if (!$current_user)
            show_404();

        $event_id = $_POST['event_id'];
        $date = DateTime::createFromFormat('Y-m-d', $_POST['date']);

        if ($this->is_new_event()) {
            $event = $this->add_event();
        }
        else {
            $event = db()->table('events')->row($event_id);
        }

        if ($event) {
            Flow::set(new CheckinFlow($event->id));

            /* @var $checkin_engine CheckinEngine */
            $this->checkin_engine->checkin_user_to_event($current_user, $event);

            flash::message("You checked into " . $event->name . '.');

            $has_sent_invites = $this->invite_engine->has_sent_invites($event, $current_user);
        }

        if (!$event) {
            redirect("day/" . $date->format('Ymd'));
        }
        elseif ($event->deal == null && browser::is_mobile()) {
            app()->goto_event($event);
        }
        elseif ($event->deal != null) {
            js()->whowentout->dialogDelay = 5000;
            redirect("events/$event->id/deal");
        }
        elseif (!$has_sent_invites) {
            js()->whowentout->dialogDelay = 5000;
            redirect("events/$event->id/invite");
        }
        else {
            app()->goto_event($event);
        }
    }

    protected function is_new_event()
    {
        return $_POST['event_id'] == 'new' || $_POST['op'] == 'add';
    }

    protected function add_event()
    {
        $user = auth()->current_user();
        $event_name = $_POST['event']['name'];
        $date = @DateTime::createFromFormat('Y-m-d', $_POST['event']['date']);

        if (!$event_name) {
            flash::error("You must enter a name for the event!");
            return false;
        }

        assert($date != null);

        $row = db()->table('events')->create_row(array(
            'place_id' => 1, //unknown
            'user_id' => $user->id,
            'name' => $event_name,
            'date' => $date,
        ));

        return $row;
    }

}
