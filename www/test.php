<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';
boot();

$filepath = APPPATH . 'whowentout/checkin/tests/checkin_engine_tests.yml';
krumo::dump(file_exists($filepath));
krumo::dump(realpath($filepath));
?>

<pre>
    <?= file_get_contents($filepath) ?>;
</pre>

<?php
unlink(APPPATH . 'whowentout/checkin/tests/checkin_engine_tests.yml');
?>
