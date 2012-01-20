<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';
boot();

$config = app()->index()->get_resources_of_type('config');

krumo::dump($config);

?>