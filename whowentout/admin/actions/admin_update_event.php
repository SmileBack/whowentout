<?php

class AdminUpdateEventAction extends Action
{

    function execute()
    {
        auth()->require_admin();

        $event_attributes = $_POST['event'];

        $event = to::event($event_attributes['id']);

        $event->place_id = $event_attributes['place_id'];
        $event->deal = $event_attributes['deal'];
        $event->deal_type = $event_attributes['deal_type'];
        $event->date = $this->parse_date($event_attributes['date']);
        $event->priority = intval($event_attributes['priority']);

        $event->save();

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
