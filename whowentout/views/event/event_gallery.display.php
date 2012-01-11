<?php

/**
 * @property $date DateTime  date to display checkins for
 * @property $user DatabaseRow  user who the display is for
 */
class Event_Gallery extends Display
{

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    /* @var $database Database */
    private $db;

    function __construct($template_name, $options = array())
    {
        parent::__construct($template_name, $options);

        $this->checkin_engine = factory()->build('checkin_engine');
        $this->db = db();
    }

    function process()
    {
        $this->checkins = $this->get_days_checkins_for_event($this->date, $this->user, $this->checkin_engine);
    }

    function get_days_checkins_for_event()
    {
        $days_checkins = array();

        $events_on_date = db()->table('events')
                              ->where('date', $this->date);

        $this->checkin = $this->checkin_engine->get_checkin_on_date($this->user, $this->date);

        foreach ($events_on_date as $cur_event) {
            $event_checkins = $this->checkin_engine->get_checkins_for_event($cur_event);
            foreach ($event_checkins as $checkin) {
                $days_checkins[] = $checkin;
            }
        }

        usort($days_checkins, array($this, 'checkin_sort_comparison'));

        return $days_checkins;
    }

    function checkin_sort_comparison($a, $b)
    {
        return $this->checkin_sort_value($b) - $this->checkin_sort_value($a);
    }

    function checkin_sort_value($checkin)
    {
        $value = 0;

        if ($checkin->user == $this->user)
            $value += 1 << 3;

        if ($checkin->event == $this->checkin->event)
            $value += 1 << 2;

        return $value;
    }

}
