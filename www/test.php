<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$contents = file_get_contents('./../whowentout/config/app.yml');
?>
<pre><?= $contents ?></pre>

