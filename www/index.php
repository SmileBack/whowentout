<?php

define('FIREPATH', dirname(__FILE__) . '/../fire/');
define('APPPATH', dirname(__FILE__) . '/../');

require_once FIREPATH . 'debug/krumo.class.php';
require_once FIREPATH . 'core/core.functions.php';

print environment();

