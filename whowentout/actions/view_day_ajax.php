<?php

class ViewDayAjax extends Action
{

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    function __construct()
    {
        $this->checkin_engine = build('checkin_engine');
    }

    function execute($date)
    {
        $date = DateTime::createFromFormat('Ymd', $date);
        $date = new XDateTime($date->format('Y-m-d'));
        $date->setTime(0, 0, 0);

        print r::event_day(array(
            'checkin_engine' => $this->checkin_engine,
            'current_user' => auth()->current_user(),
            'date' => $date,
        ));
    }
}
