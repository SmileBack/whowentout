<?php

define('FIREPATH', dirname(__FILE__) . '/../fire/');
define('APPPATH', dirname(__FILE__) . '/../');

require_once FIREPATH . 'debug/krumo.class.php';
require_once FIREPATH . 'core/core.functions.php';

//$session_handler = factory()->build('session_handler');
//session_set_save_handler(
//    array($session_handler, 'open'),
//    array($session_handler, 'close'),
//    array($session_handler, 'read'),
//    array($session_handler, 'write'),
//    array($session_handler, 'destroy'),
//    array($session_handler, 'gc')
//);
//session_start();

route_uri_request();
