<?php

class ViewEventGalleryAction extends Action
{

    /**
     * @var CheckinEngine
     */
    private $checkin_engine;

    function __construct()
    {
        $this->auth = auth();
        $this->checkin_engine = build('checkin_engine');
    }

    function execute($date, $who)
    {
        $current_user = $this->auth->current_user();
        $date = $this->parse_date($date);
        $checkin = $this->checkin_engine->get_checkin_on_date($current_user, $date);
        $filter_friends = ($who == 'friends');

        print r::event_gallery(array(
            'date' => $date,
            'user' => $current_user,
            'filter_friends' => $filter_friends,
            'selected_event' => $checkin->event,
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
