<?php
/* @var $date DateTime */
?>
<span class="relative date" title="<?= $date->format(DATE_ISO8601); ?>">
    <?= $date->format('D, M j') ?>
</span>
