<?php

class Door_Tests extends TestGroup
{

    function setup()
    {
        parent::teardown();
        $this->tz = new DateTimeZone('America/New_York');
    }

    function teardown()
    {
        parent::teardown();
    }

    function test_get_opening_time()
    {
        $clock = new Clock($this->tz);
        $door = new Door($clock);

        $test_cases = array(
            '2011-10-27' => array( //thursday
                '01:00:00' => '2011-10-28 02:00:00',
                '02:00:00' => '2011-10-28 02:00:00',
                '22:00:00' => '2011-10-28 02:00:00',
            ),
            '2011-10-28' => array( //friday
                '01:00:00' => '2011-10-28 02:00:00',
                '02:00:00' => '2011-10-29 02:00:00',
                '22:00:00' => '2011-10-29 02:00:00',
            ),
            '2011-10-29' => array( //saturday
                '01:00:00' => '2011-10-29 02:00:00',
                '02:00:00' => '2011-10-30 02:00:00',
                '22:00:00' => '2011-10-30 02:00:00',
            ),
            '2011-10-30' => array( //sunday
                '01:00:00' => '2011-10-30 02:00:00',
                '02:00:00' => '2011-11-04 02:00:00',
                '22:00:00' => '2011-11-04 02:00:00',
            ),
        );

        foreach ($test_cases as $date => $times) {
            foreach ($times as $time => $expected_opening_time) {
                $clock->set_time($date . ' ' . $time);
                $actual_opening_time = $door->get_opening_time();
                $this->assert_equal(
                    $actual_opening_time->format('Y-m-d H:i:s'),
                    $expected_opening_time,
                    "opening time on $date $time should be $expected_opening_time"
                );
            }
        }
    }

    function test_get_closing_time()
    {
        $clock = new Clock($this->tz);
        $door = new Door($clock);

        $test_cases = array(
            '2011-10-27' => array( //thursday
                '00:00:00' => '2011-10-29 00:00:00',
                '01:00:00' => '2011-10-29 00:00:00',
                '08:00:00' => '2011-10-29 00:00:00',
                '23:59:59' => '2011-10-29 00:00:00',
            ),
            '2011-10-28' => array( //friday
                '00:00:00' => '2011-10-29 00:00:00',
                '01:00:00' => '2011-10-29 00:00:00',
                '02:00:00' => '2011-10-29 00:00:00',
                '08:00:00' => '2011-10-29 00:00:00',
                '23:59:59' => '2011-10-29 00:00:00',
            ),
            '2011-10-29' => array( //saturday
                '00:00:00' => '2011-10-30 00:00:00',
                '01:00:00' => '2011-10-30 00:00:00',
                '02:00:00' => '2011-10-30 00:00:00',
                '23:59:59' => '2011-10-30 00:00:00',
            ),
            '2011-10-30' => array( //sunday
                '00:00:00' => '2011-10-31 00:00:00',
                '01:00:00' => '2011-10-31 00:00:00',
                '02:00:00' => '2011-10-31 00:00:00',
                '23:59:59' => '2011-10-31 00:00:00',
            ),
            '2011-10-31' => array( //monday
                '00:00:00' => '2011-11-05 00:00:00',
                '01:00:00' => '2011-11-05 00:00:00',
                '02:00:00' => '2011-11-05 00:00:00',
                '23:59:59' => '2011-11-05 00:00:00',
            ),
        );

        foreach ($test_cases as $date => $times) {
            foreach ($times as $time => $expected_closing_time) {
                $clock->set_time($date . ' ' . $time);
                $actual_closing_time = $door->get_closing_time();
                $this->assert_equal(
                    $actual_closing_time->format('Y-m-d H:i:s'),
                    $expected_closing_time,
                    "closing time on $date $time should be $expected_closing_time"
                );
            }
        }

    }

    function test_door_open_states()
    {
        $test_cases = array(
            '2011-10-27' => array( //thursday
                '00:00:00' => FALSE,
                '00:00:01' => FALSE,
                '01:59:59' => FALSE,
                '02:00:00' => FALSE,
                '02:00:01' => FALSE,
                '12:00:00' => FALSE,
                '23:59:59' => FALSE,
            ),
            '2011-10-28' => array( //friday
                '00:00:00' => FALSE,
                '00:00:01' => FALSE,
                '01:59:59' => FALSE,
                '02:00:00' => TRUE,
                '02:00:01' => TRUE,
                '12:00:00' => TRUE,
                '23:59:59' => TRUE,
            ),
            '2011-10-29' => array( //saturday
                '00:00:00' => FALSE,
                '00:00:01' => FALSE,
                '01:59:59' => FALSE,
                '02:00:00' => TRUE,
                '02:00:01' => TRUE,
                '12:00:00' => TRUE,
                '23:59:59' => TRUE,
            ),
            '2011-10-30' => array( //sunday
                '00:00:00' => FALSE,
                '00:00:01' => FALSE,
                '01:59:59' => FALSE,
                '02:00:00' => TRUE,
                '02:00:01' => TRUE,
                '12:00:00' => TRUE,
                '23:59:59' => TRUE,
            ),
            '2011-10-31' => array( //monday
                '00:00:00' => FALSE,
                '00:00:01' => FALSE,
                '01:59:59' => FALSE,
                '02:00:00' => FALSE,
                '02:00:01' => FALSE,
                '12:00:00' => FALSE,
                '23:59:59' => FALSE,
            ),
        );
        
        $clock = new Clock($this->tz);
        $door = new Door($clock);
        foreach ($test_cases as $date => $cases) {
            foreach ($cases as $time_of_day => $door_is_open) {
                $clock->set_time($date . ' ' . $time_of_day);
                $this->assert_equal(
                    $door->is_open(),
                    $door_is_open,
                    $door_is_open ? "doors are open at $date $time_of_day"
                            : "doors are closed at $date $time_of_day"
                );
            }
        }
    }

}
