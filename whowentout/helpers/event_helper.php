<?php

function raise_event($event_name, $data = array()) {
  $ci =& get_instance();
  $ci->event->raise($event_name, $data);
}
