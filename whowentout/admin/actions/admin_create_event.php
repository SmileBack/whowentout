<?php

class AdminCreateEventAction extends Action
{

    function create()
    {
        auth()->require_admin();

        $place_attributes = $_POST['event'];
        $place_attributes['date'] = $this->parse_date($place_attributes['date']);

        app()->database()->table('events')->create_row($place_attributes);
        redirect('admin/events');
    }

    /**
     * @param $date_string
     * @return DateTime
     */
    private function parse_date($date_string)
    {
        $timestamp = strtotime($date_string, app()->clock()->get_time()->getTimestamp());
        $date = DateTime::createFromFormat('U', $timestamp);
        return $date;
    }

}
