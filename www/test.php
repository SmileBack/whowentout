<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';

if (environment() != 'whowentout')
    app()->clock()->set_time(new DateTime('2011-12-08'));
else
    app()->clock()->set_time(new DateTime('now'));

$dt = new DateTime('now');

print $dt->format('Y-m-d H:i:s');

print "<br>";

print app()->clock()->today()->format('Y-m-d H:i:s');
