<?php

class ViewDayAction extends Action
{

    public $url = 'day/(:any)';

    /* @var $auth FacebookAuth */
    private $auth;

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    function __construct()
    {
        $this->auth = build('auth');
        $this->checkin_engine = build('checkin_engine');
    }

    function execute($date = null)
    {
        $current_user = $this->auth->current_user();

        if ($date == null) {
            $date = app()->clock()->today();
            redirect('day/' . $date->format('Ymd'));
        }
        else {
            $date = DateTime::createFromFormat('Ymd', $date);
            $date = new XDateTime($date->format('Y-m-d'));
            $date->setTime(0, 0, 0);
        }

        print r::page(array(
            'content' => r::events_date_selector(array('selected_date' => $date))
                    . r::event_day(array(
                        'checkin_engine' => $this->checkin_engine,
                        'current_user' => $current_user,
                        'date' => $date,
                    )),
        ));

    }

}
