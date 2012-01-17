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

        $this->checkin_engine = build('checkin_engine');
        $this->db = db();
    }

    function process()
    {
        $this->checkin = $this->checkin_engine->get_checkin_on_date($this->user, $this->date);

        if ($this->checkin)
            $this->checkins = $this->get_days_checkins_for_event($this->date, $this->user, $this->checkin_engine);

        $this->hidden = ($this->checkin == null);
    }

    private function get_days_checkins_for_event()
    {
        $days_checkins = array();

        $events_on_date = db()->table('events')
                              ->where('date', $this->date);

        $checkin_count = array();

        foreach ($events_on_date as $cur_event) {
            $event_checkins = $this->checkin_engine->get_checkins_for_event($cur_event);
            $checkin_count[$cur_event->id] = count($event_checkins);
            foreach ($event_checkins as $checkin) {
                $days_checkins[] = $checkin;
            }
        }

        $this->checkin_count = $checkin_count;
        usort($days_checkins, array($this, 'checkin_sort_comparison'));

        return $days_checkins;
    }

    private function checkin_sort_comparison($a, $b)
    {
        return $this->checkin_sort_value($b) - $this->checkin_sort_value($a);
    }

    private function checkin_sort_value($checkin)
    {
        $value = 0;

        if ($checkin->user == $this->user)
            $value += 1 << 17;

        if ($checkin->event == $this->checkin->event)
            $value += 1 << 16;

        $value += $this->get_num_checkins($checkin->event);

        return $value;
    }

    private function get_num_checkins($event)
    {
        return $this->checkin_count[$event->id];
    }

}
