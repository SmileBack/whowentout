<?php
/**
 * @var $invite DatabaseRow
 */
?>
<?= $invite->sender->first_name ?> <?= $invite->sender->last_name ?> invited you to attend <?= $invite->event->name ?> on <?= $invite->event->date->format('l') ?>