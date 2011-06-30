<?php

define('CURRENT_TIME', '2011-05-27 22:06:04 -0700');

/**
 * @return DateTime
 */
function current_time($local = FALSE) {
  if (defined('CURRENT_TIME')) {
    $dt = new DateTime(CURRENT_TIME);
  }
  else {
    $dt = new DateTime(null, new DateTimeZone('UTC'));
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
