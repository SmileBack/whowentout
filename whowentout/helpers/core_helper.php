<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function string_ends_with($end_of_string, $string) {
  return substr($string, -strlen($end_of_string)) === $end_of_string;
}

function string_starts_with($start_of_string, $source) {
   return strncmp($source, $start_of_string, strlen($start_of_string)) == 0;
}

function string_after_first($needle, $haystack) {
  $pos = strpos($haystack, $needle);
  if ($pos === FALSE) {
    return FALSE;
  } else {
    return substr($haystack, $pos + strlen($needle));
  }
}

function string_before_first($needle, $haystack) {
  $pos = strpos($haystack, $needle);
  if ($pos === FALSE) {
    return FALSE;
  } else {
    return substr($haystack, 0, $pos);
  }
}

function string_after_last($needle, $haystack) {
  $pos = strrpos($haystack, $needle);
  if ($pos === FALSE) {
    return FALSE;
  } else {
    return substr($haystack, $pos + strlen($needle));
  }
}

function string_before_last($needle, $haystack) {
  $pos = strrpos($haystack, $needle);
  if ($pos === FALSE) {
    return FALSE;
  } else {
    return substr($haystack, 0, $pos);
  }
}