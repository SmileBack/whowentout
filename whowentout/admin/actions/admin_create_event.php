<?php

class AdminCreateEventAction extends Action
{

    function execute()
    {
        auth()->require_admin();

        $event_attributes = $_POST['event'];

        $dates = $this->parse_dates($event_attributes['date']);

        if (!$event_attributes['deal_ticket'])
            $event_attributes['deal_ticket'] = $event_attributes['deal'];

        foreach ($dates as $date) {
            $event_attributes['date'] = $date;
            app()->database()->table('events')->create_row($event_attributes);
        }
        
        redirect('admin/events');
    }

    /**
     * @param $date_strings
     * @return DateTime[]
     */
    private function parse_dates($date_strings)
    {
        $dates = array();
        foreach (preg_split('/\s*,\s*/', $date_strings) as $date_string) {
            $dates[] = $this->parse_date($date_string);
        }
        return $dates;
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
