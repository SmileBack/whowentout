<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';
boot();

print app()->clock()->today()->format('Y-m-d H:i:s');
