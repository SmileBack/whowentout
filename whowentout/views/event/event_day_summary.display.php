<?php

class Event_Day_Summary_Display extends Display
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
    }

}
