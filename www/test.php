<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';
boot();

$checkins = db()->table('checkins')->where('user.first_name', 'Venkat')
                                   ->order_by('time', 'desc');
?>
<li>
    <?php foreach ($checkins as $c): ?>
        <li><?= $c->event->name ?> on <?= $c->event->date->format('Y-m-d') ?>, (<?= $c->event->place->name ?>)</li>
    <?php endforeach; ?>
</li>
