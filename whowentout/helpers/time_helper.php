<?php

function time_delta_seconds() {
  if (time_is_faked()) {
    $fake_time_point = get_option('fake_time_point');
    $delta = $fake_time_point['fake_time']->getTimestamp() - $fake_time_point['real_time']->getTimestamp();
    return $delta;
  }
  return 0;
}

/**
 * @return DateTime
 */
function actual_time($local = FALSE) {
  $dt = new DateTime(null, new DateTimeZone('UTC'));
  return $local ? make_local($dt) : make_gmt($dt);
}

/**
 * @return DateTime
 */
function current_time($local = FALSE) {
  $dt = actual_time();
  
  if ( time_is_faked() ) {
    $fake_time_point = get_option('fake_time_point');
    $delta = time_delta_seconds();
    $dt = $dt->modify("+$delta seconds");
  }
  
  return $local ? make_local($dt) : make_gmt($dt);
}

function set_fake_time(DateTime $fake_time) {
  $fake_time = make_gmt($fake_time);
  $fake_time_point = array(
    'fake_time' => $fake_time,
    'real_time' => actual_time(),
  );
  set_option('fake_time_point', $fake_time_point);
}

function set_fake_time_of_day($h, $m = 0, $s = 0) {
  $time = current_time();
  $time->setTime($h, $m, $s);
  set_fake_time($time);
  return $time;
}

function time_is_faked() {
  return option_exists('fake_time_point');
}

function unset_fake_time() {
  unset_option('fake_time_point');
}

function make_gmt($time) {
  $time = clone $time;
  $time->setTimezone(new DateTimeZone('UTC'));
  return $time;
}

function make_local($time) {
  $time = clone $time;
  $time->setTimezone( college()->timezone );
  return $time;
}

/**
 * Return the GMT time for when the doors at the current college are next open for checkin.
 * @return DateTime
 */
function get_opening_time($local = FALSE) {
  $opening_time = today(TRUE)->setTime(1, 0, 0);
  return $local ? make_local($opening_time) : make_gmt($opening_time);
}

/**
 * Return the GMT time for when the doors at the current college are next closed for checkin.
 * @return DateTime
 */
function get_closing_time($local = FALSE) {
  $closing_time = today(TRUE)->setTime(12 + 11, 0, 0);
  return $local ? make_local($closing_time) : make_gmt($closing_time);
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
  return !doors_are_open();
}

function doors_are_open() {
  $current = current_time();
  $open = get_opening_time();
  $close = get_closing_time();
  
  return $open <= $current && $current < $close;
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
