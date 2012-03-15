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
        if (!$date) {
            $date = app()->clock()->today();
        }
        else {
            $date = $this->parse_date($date);
        }

        $response = array();
        $response['event_day'] = r::event_day(array(
            'checkin_engine' => $this->checkin_engine,
            'current_user' => auth()->current_user(),
            'date' => $date,
        ))->render();

        $checkin = $this->checkin_engine->get_checkin_on_date(auth()->current_user(), $date);
        $top_parties = $this->checkin_engine->get_top_parties($date);

        $response['date'] = $date->getTimestamp();
        $response['event'] = $checkin ? to::json($checkin->event) : null;

        $response['top_parties'] = to::json($top_parties);

        print json_encode($response);exit;
    }

    function execute_no_ajax($date = null)
    {
        $current_user = $this->auth->current_user();

        if (!$date) {
            $date = app()->clock()->today();
            redirect('day/' . $date->format('Ymd'));
        }
        else {
            $date = $this->parse_date($date);
        }

        print r::page(array(
            'content' => r::events_date_selector(array('selected_date' => $date))
                    . '<div class="event_day"></div>',
        ));
    }

    /**
     * @param $date_string
     * @return XDateTime
     */
    function parse_date($date_string)
    {
        /* @var $timezone DateTimeZone */
        $timezone = build('timezone');
        $date = DateTime::createFromFormat('Ymd', $date_string);

        $date = new XDateTime($date->format('Y-m-d'), $timezone);
        $date->setTime(0, 0, 0);
        return $date;
    }

}
