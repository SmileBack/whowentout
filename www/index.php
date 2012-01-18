<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('FIREPATH', dirname(__FILE__) . '/../fire/');
define('APPPATH', dirname(__FILE__) . '/../');

require_once FIREPATH . 'debug/krumo.class.php';
require_once FIREPATH . 'core/core.functions.php';

if (db()->has_table('sessions')) {
    $session_handler = factory()->build('session_handler');
    session_set_save_handler(
        array($session_handler, 'open'),
        array($session_handler, 'close'),
        array($session_handler, 'read'),
        array($session_handler, 'write'),
        array($session_handler, 'destroy'),
        array($session_handler, 'gc')
    );
}

session_start();

app()->clock()->set_time(new DateTime('2011-12-09'));
app()->trigger('boot');

route_uri_request();
