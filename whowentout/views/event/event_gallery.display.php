<?php

class Event_Gallery_Display extends Display
{


    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    /* @var $database Database */
    private $db;

    protected $defaults = array('filter_friends' => false);

    function __construct($template_name, $options = array())
    {
        parent::__construct($template_name, $options);

        $this->checkin_engine = build('checkin_engine');
        $this->db = db();
    }

    function process()
    {
        $this->checkin = $this->checkin_engine->get_checkin_on_date($this->user, $this->date);

        $friends = $this->friends = $this->fetch_friends($this->user);
        $checkins = $this->checkin_engine->get_checkins_on_date($this->date);

        $this->checkins = $checkins;

        if ($this->filter_friends) {
            $checkins = array_filter($checkins, function($checkin) use ($friends) {
                return isset($friends[$checkin->user->id]);
            });
        }

        benchmark::start('sort_checkins');
        usort($checkins, array($this, 'checkin_sort_comparison'));
        benchmark::end('sort_checkins');

        $this->checkins = $checkins;
    }

    private function fetch_friends($user)
    {
        $friends = array();
        foreach ($user->friends as $friend) {
            $friends[$friend->id] = $friend;
        }
        return $friends;
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

        if ($this->checkin && $checkin->event == $this->checkin->event)
            $value += 1 << 16;

        benchmark::start('get_checkin_count');
        $value += $this->checkin_engine->get_checkin_count($checkin->event);
        benchmark::end('get_checkin_count');

        return $value;
    }

}
