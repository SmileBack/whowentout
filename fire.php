<?php

define('FIREPATH', './fire/');
define('APPPATH', './');

require_once FIREPATH . 'core/core.functions.php';

run_command($argv);
