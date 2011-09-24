<?php

function time_delta_seconds()
{
    if (time_is_faked()) {
        $fake_time_point = ci()->option->get('fake_time_point');
        $delta = $fake_time_point['fake_time']->getTimestamp() - $fake_time_point['real_time']->getTimestamp();
        return $delta;
    }
    return 0;
}

/**
 * @return DateTime
 */
function actual_time()
{
    $dt = new DateTime(null, new DateTimeZone('UTC'));
    return make_gmt($dt);
}

/**
 * @return DateTime
 */
function current_time()
{
    $dt = actual_time();

    if (time_is_faked()) {
        $fake_time_point = ci()->option->get('fake_time_point');
        $delta = time_delta_seconds();
        $dt = $dt->modify("+$delta seconds");
    }

    return make_gmt($dt);
}

function set_fake_time(DateTime $fake_time)
{
    $fake_time = make_gmt($fake_time);
    $real_time = actual_time();
    $fake_time_point = array(
        'fake_time' => $fake_time,
        'real_time' => $real_time,
    );
    ci()->option->set('fake_time_point', $fake_time_point);
    
    raise_event('time_faked', array(
                                'fake_time' => $fake_time,
                                'real_time' => $real_time,
                              ));
}

function set_fake_time_of_day($h, $m = 0, $s = 0)
{
    $time = current_time();
    $time->setTime($h, $m, $s);
    set_fake_time($time);
    return $time;
}

function time_is_faked()
{
    return ci()->option->exists('fake_time_point');
}

function unset_fake_time()
{
    ci()->option->delete('fake_time_point');
}

function make_gmt($time)
{
    $time = clone $time;
    $time->setTimezone(new DateTimeZone('UTC'));
    return $time;
}

