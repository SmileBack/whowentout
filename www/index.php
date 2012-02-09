<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';

if (environment() != 'whowentout')
    app()->clock()->set_time(new DateTime('2011-12-08'));

app()->trigger('boot');

if (db()->has_table('sessions')) {
    $session_handler = build('session_handler');
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

route_uri_request();
