<?php

class Clock_Tests extends TestGroup
{

    function setup()
    {
        parent::setup();

        $this->tz = new DateTimeZone('America/New_York');
    }

    function test_clock_basic()
    {
        $clock = new Clock( $this->tz );
        $clock->set_time( new DateTime('2011-10-20 09:30:00') );

        $this->assert_equal($clock->get_time()->format('Y-m-d H:i:s'), '2011-10-20 09:30:00');
    }

    function test_clock_current_time()
    {
        $clock = new Clock( $this->tz );
        $clock->set_time('now');

        $current_time = new DateTime('now', $this->tz);

        $this->assert_equal($clock->get_time()->format('Y-m-d H:i:s'), $current_time->format('Y-m-d H:i:s'));
    }
    
}
