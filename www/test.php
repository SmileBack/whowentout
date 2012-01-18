<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';
boot();

?>

<?php if (browser::is_mobile()): ?>
        <h1>you are on a mobile browser</h1>
<?php else: ?>
        <h1>you are <em>not</em> on a mobile browser</h1>
<?php endif; ?>

