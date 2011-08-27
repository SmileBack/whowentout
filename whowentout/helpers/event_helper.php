<?php

function raise_event($event, $arg1 = NULL, $arg2 = NULL, $arg3 = NULL) {
  $event_handler = "on_{$event}";
  if ( function_exists($event_handler) ) {
    $args = func_get_args(); unset($args[0]);
    call_user_func_array($event_handler, $args);
  }
}
