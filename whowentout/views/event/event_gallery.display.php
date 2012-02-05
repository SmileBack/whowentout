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

        if ($this->checkin) {
            $checkins = $this->checkin_engine->get_checkins_on_date($this->checkin->event->date);
            usort($checkins, array($this, 'checkin_sort_comparison'));
            $this->checkins = $checkins;
        }

        $this->hidden = ($this->checkin == null);
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

        $value += $this->checkin_engine->get_checkin_count($checkin->event);

        return $value;
    }

}
