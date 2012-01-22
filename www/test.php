<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';
boot();

krumo::dump(file_exists(APPPATH . 'whowentout/checkin/tests/checkin_engine_tests.yml'));

unlink(APPPATH . 'whowentout/checkin/tests/checkin_engine_tests.yml');

?>