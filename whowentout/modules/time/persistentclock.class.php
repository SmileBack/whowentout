<?php

class PersistentClock extends Clock
{

    private $ci;
    private $dont_save_delta = FALSE;

    function __construct(DateTimeZone $timezone)
    {
        parent::__construct($timezone);

        $this->ci =& get_instance();
        $this->check_for_requirements();
        $this->restore_time_from_database();
    }

    function set_delta($seconds)
    {
        parent::set_delta($seconds);

        if (!$this->dont_save_delta)
            $this->ci->option->set('fake_time_delta', $this->get_delta());
    }

    private function check_for_requirements()
    {
        if (!$this->ci->option)
            throw new Exception("PersistentClock requires the option library.");
    }

    private function restore_time_from_database()
    {
        $time_delta = $this->ci->option->get('fake_time_delta', 0);

        $this->dont_save_delta = TRUE;
        $this->set_delta($time_delta);
        $this->dont_save_delta = FALSE;
    }

}
