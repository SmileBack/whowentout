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
        $this->checkins = $checkins = $this->checkin_engine->get_all_checkins_on_date($this->date, $this->user, 0, 300);

        if ($this->filter_friends) {
            $checkins = array_filter($checkins, function($checkin) {
                return $checkin->is_friend;
            });
        }

        $this->checkins = $checkins;
    }

}
