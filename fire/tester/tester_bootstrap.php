<?php

define('FIREPATH', dirname(__FILE__) . '/../');
define('APPPATH', dirname(__FILE__) . '/../../');

putenv('environment=localhost');

require_once FIREPATH . 'debug/krumo.class.php';
require_once FIREPATH . 'core/core.functions.php';
