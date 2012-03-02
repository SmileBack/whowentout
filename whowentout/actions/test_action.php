<?php

class TestAction extends Action
{

    /**
     * @var Database
     */
    private $database;

    function execute()
    {
        print benchmark::memory_used();
    }

    function email_deal($user, $event)
    {
        /* @var $deal_emailer DealEmailer */
        $deal_emailer = build('deal_emailer');
        $deal_emailer->email_deal($user, $event);
    }

    function get_undecided_event(DateTime $date)
    {
        $undecided_place = $this->database->table('places')->where('type', 'undecided base')->first();

        $undecided_event = $this->database->table('events')->where('place_id', $undecided_place->id)
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
