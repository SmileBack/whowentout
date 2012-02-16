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
        benchmark::start('get_checkin_on_date');
        $this->checkin = $this->checkin_engine->get_checkin_on_date($this->user, $this->date);
        benchmark::end('get_checkin_on_date');

        $this->hidden = ($this->checkin == null);
        $friends = $this->friends = $this->fetch_friends($this->user);

        benchmark::start('get_checkins_on_date');
        $checkins = $this->checkin_engine->get_checkins_on_date($this->date);
        benchmark::end('get_checkins_on_date');

        benchmark::start('sort_checkins');
        usort($checkins, array($this, 'checkin_sort_comparison'));
        benchmark::end('sort_checkins');

        $this->checkins = $checkins;
        $this->friend_checkins = array_filter($this->checkins, function($checkin) use ($friends) {
            return isset($friends[$checkin->user->id]);
        });
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
