<?php

class CheckinUndecidedAction extends Action
{

    /* @var $database Database */
    private $database;

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    private $current_user;

    function __construct()
    {
        $this->database = db();
        $this->checkin_engine = build('checkin_engine');
        $this->current_user = auth()->current_user();
    }

    function execute()
    {
        $date = @DateTime::createFromFormat('Y-m-d', $_POST['date']);
        $undecided_event = $this->get_undecided_event($date);

        $this->checkin_engine->checkin_user_to_event($this->current_user, $undecided_event);

        flash::message("You are not sure yet about your plans.");

        app()->goto_event($undecided_event);
    }

    function get_undecided_event(DateTime $date)
    {
        $undecided_place = $this->database->table('places')->where('type', 'undecided base');
        $undecided_event = $this->database->table('events')->where('place.id', $undecided_place->id)
                                          ->where('date', $date)->first();

        if (!$undecided_event) {
            $undecided_event = $this->database->table('events')->create_row(array(
                'date' => $date,
                'name' => 'Not Sure Yet',
                'place_id' => $undecided_place->id,
            ));
        }

        return $undecided_event;
    }

}
