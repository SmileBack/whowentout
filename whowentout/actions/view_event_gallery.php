<?php

class ViewEventGalleryAction extends Action
{

    function execute($date)
    {
        $current_user = auth()->current_user();
        $date = $this->parse_date($date);
        print r::event_gallery(array(
            'date' => $date,
            'user' => $current_user,
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
