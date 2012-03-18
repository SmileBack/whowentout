<?php

class CheckinAction extends Action
{

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    function __construct()
    {
        $this->checkin_engine = build('checkin_engine');
    }

    function execute()
    {
        $current_user = auth()->current_user();

        if (!$current_user)
            show_404();

        $event_id = $_POST['event_id'];

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

            flash::message($this->get_checkin_message($event, $current_user));
        }

        app()->goto_event($event);
    }

    protected function get_checkin_message($event, $user)
    {
        if ($event->deal)
            return "Your deal for $event->name will be sent to $user->email.";
        else
            return "You have joined $event->name.";
    }

    protected function is_new_event()
    {
        return $_POST['event_id'] == 'new';
    }

    protected function add_event()
    {
        $user = auth()->current_user();

        $event_name = $_POST['event']['name'];

        $event_place_type = $_POST['type'];
        $event_place = db()->table('places')->where('type', $event_place_type)->first();

        $date = @DateTime::createFromFormat('Y-m-d', $_POST['event']['date']);

        if (!$event_name) {
            flash::error("You must enter a name for the event!");
            return false;
        }

        assert($date != null);
        assert($event_place != null);

        $row = db()->table('events')->create_row(array(
            'place_id' => $event_place->id,
            'user_id' => $user->id,
            'name' => $event_name,
            'date' => $date,
        ));

        return $row;
    }

}
