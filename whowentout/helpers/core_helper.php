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

function folders($path, $include_subdirectories = FALSE) {
    if ( ! is_dir($path) )
    return FALSE;

  $folders = array();

  $iterator = $include_subdirectories
            ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST)
            : new DirectoryIterator($path);

  foreach ($iterator as $file) {
    // isDot method is only available in DirectoryIterator items
    // isDot check skips '.' and '..'
    if ($include_subdirectories == FALSE && $file->isDot())
      continue;

    if ($file->isDir()) {
      // Standardize to forward slashes
      $folders[] = str_replace('\\', '/', $file->getPathName());
    }
  }

  return $folders;
}

function files($path, $include_subdirectories = FALSE) {
  if ( ! is_dir($path))
    return FALSE;

  $files = array();

  $iterator = $include_subdirectories
            ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path))
            : new DirectoryIterator($path);
            
  foreach ($iterator as $file) {
    // isDot method is only available in DirectoryIterator items
    // isDot check skips '.' and '..'
    if ($include_subdirectories == FALSE && $file->isDot())
      continue;
    // Standardize to forward slashes
    $files[] = str_replace('\\', '/', $file->getPathName());
  }
  
  return $files;
}

