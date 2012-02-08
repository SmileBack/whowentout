<?php

class AdminUpdateEventAction extends Action
{

    function execute()
    {
        auth()->require_admin();

        krumo::dump($_POST);

        $event_attributes = $_POST['event'];

        $id = $event_attributes['id'];
        $event = db()->table('events')->row($id);

        $event->place_id = $event_attributes['place_id'];
        $event->deal = $event_attributes['deal'];
        $event->date = $this->parse_date($event_attributes['date']);

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
