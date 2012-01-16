<?php

define('FIREPATH', dirname(__FILE__) . '/../fire/');
define('APPPATH', dirname(__FILE__) . '/../');

print "<h1>got here (define paths)</h1>";
print '<h2>' . FIREPATH . '</h2>';
print '<br/>';
print '<h2>' . APPPATH . '</h2>';
print '<br/>';

require_once FIREPATH . 'debug/krumo.class.php';
require_once FIREPATH . 'core/core.functions.php';

print "<h1>got here (require core woo) </h1>";

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

print "<h1>got here (before session)</h1>";

print "<h1>environment = " . environment() . "</h1>";

session_start();

app()->clock()->set_time(new DateTime('2011-12-09'));

print "<h1>got here (after set time)</h1>";

route_uri_request();

print "<h1>got here (after routing uri request)</h1>";

