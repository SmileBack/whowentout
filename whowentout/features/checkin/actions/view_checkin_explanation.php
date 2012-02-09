<?php

class ViewCheckinExplanationAction extends Action
{

    /* @var $checkin_engine CHeckinEngine */
    private $checkin_engine;

    function __construct()
    {
        $this->current_user = auth()->current_user();
        $this->checkin_engine = build('checkin_engine');
    }

    function execute($event_id)
    {
        $event = db()->table('events')->row($event_id);

        if ($this->is_ajax())
            $this->execute_ajax($event);
        else
            $this->execute_page($event);
    }

    function execute_ajax($event)
    {
        print r::checkin_explanation(array(
            'event' => $event,
        ));
    }

    function execute_page($event)
    {
        print r::page(array(
            'content' => r::events_date_selector(array('selected_date' => $event->date))
                       . r::event_day(array(
                             'checkin_engine' => $this->checkin_engine,
                             'current_user' => $this->current_user,
                             'date' => $event->date,
                         )),
        ));
    }

}
