<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';

require_once FIREPATH . 'debug/krumo.class.php';

require_once COREPATH . 'index.class.php';

$index_cache = _build_index_cache(APPPATH . 'cache');
$index = new Index(APPPATH, $index_cache);
$index->rebuild();

krumo::dump($index->data());
