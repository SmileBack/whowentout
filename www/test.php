<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';
boot();

$contents_yml = file_get_contents('./checkin/tests/checkin_engine_tests.yml');
$contents_php = file_get_contents('./checkin/tests/checkin_engine_tests.php');
?>
<pre>
    <?= $contents_yml ?>
</pre>

<pre>
    <?= $contents_php ?>
</pre>