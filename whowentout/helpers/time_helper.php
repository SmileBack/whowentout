<?php

function time_delta_seconds() {
  if (option_exists('fake_time_point')) {
    $fake_time_point = get_option('fake_time_point');
    $delta = $fake_time_point['fake_time']->getTimestamp() - $fake_time_point['real_time']->getTimestamp();
    return $delta;
  }
  return 0;
}

/**
 * @return DateTime
 */
function current_time($local = FALSE) {
  $dt = new DateTime(null, new DateTimeZone('UTC'));
  
  if (option_exists('fake_time_point')) {
    $fake_time_point = get_option('fake_time_point');
    $delta = date_diff($fake_time_point['real_time'], $fake_time_point['fake_time']);
    $dt = $dt->add($delta);
  }
  
  return $local ? make_local($dt) : make_gmt($dt);
}

/*
 * @return DateTimeZone
 *   The timezone of the current college.
 */
function get_college_timezone() {
  return new DateTimeZone('America/Los_Angeles');
}

function make_gmt($time) {
  $time->setTimezone(new DateTimeZone('UTC'));
  return $time;
}

function make_local($time) {
  $time->setTimezone(get_college_timezone());
  return $time;
}

/**
 * Return the GMT time for when the doors at the current college open for checkin.
 * @return DateTime
 */
function get_opening_time($local = FALSE) {
  $time = today(TRUE)->setTime(1, 0, 0);
  return $local ? make_local($time) : make_gmt($time);
}

/**
 * Return the GMT time for when the doors at the current college close for checkin.
 * @return DateTime
 */
function get_closing_time($local = FALSE) {
  $time = today(TRUE)->setTime(12 + 11, 0, 0);
  return $local ? make_local($time) : make_gmt($time);
}

/**
 * @return int
 *   The number of seconds until the doors are closed. If the doors have already
 *   closed, 0 will be returned.
 */
function get_seconds_until_close() {
  $delta = get_closing_time()->getTimestamp() - current_time()->getTimestamp();
  return max($delta, 0);
}

function doors_are_closed() {
  return get_seconds_until_close() == 0;
}

/**
 * Gives you the date for today at current college (12am).
 * @param bool $local
 * @return DateTime
 */
function today($local = FALSE) {
  $current_local_time = current_time(TRUE);
  $current_local_time->setTime(0, 0, 0);
  return $local ? make_local($current_local_time) : make_gmt($current_local_time);
}

function yesterday($local = FALSE) {
  $current_local_time = current_time(TRUE);
  $current_local_time->setTime(0, 0, 0);
  $current_local_time->modify('-1 day');
  return $local ? make_local($current_local_time) : make_gmt($current_local_time);
}
