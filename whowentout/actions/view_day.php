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
        flow::end();

        if ($this->is_ajax())
            return $this->execute_ajax($date);
        else
            return $this->execute_no_ajax($date);
    }

    function execute_ajax($date)
    {
        if (!$date)
            $date = app()->clock()->today();

        $date = DateTime::createFromFormat('Ymd', $date);
        $date = new XDateTime($date->format('Y-m-d'));
        $date->setTime(0, 0, 0);

        print r::event_day(array(
            'checkin_engine' => $this->checkin_engine,
            'current_user' => auth()->current_user(),
            'date' => $date,
        ));
    }

    function execute_no_ajax($date = null)
    {
        if (!$date)
            $date = app()->clock()->today();

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
