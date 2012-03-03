<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';

require_once FIREPATH . 'debug/krumo.class.php';

require_once COREPATH . 'meta/metadata.class.php';
require_once COREPATH . 'meta/directory_metadata.class.php';
require_once COREPATH . 'meta/file_metadata.class.php';

require_once COREPATH . 'indexers/indexer.class.php';
require_once COREPATH . 'indexers/directory_indexer.class.php';


$root = new DirectoryMetadata();
$root->type = 'directory';
$root->name = 'root';
$root->directory_path = realpath('../');

$directory_indexer = new DirectoryIndexer();
$directory_indexer->index($root);

krumo::dump($root);
