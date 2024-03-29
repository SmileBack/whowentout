<?php
/* @var $selected_date XDateTime */
$capacity = 28;
$start_index = $capacity;
$today = app()->clock()->today();
?>
<div id="events_date_selector">
    <a href="/" class="prev">Prev</a>

    <div class="scrollable" data-index="<?= $start_index ?>">
        <div class="items">
            <?php for ($n = -$capacity; $n <= +$capacity; $n++): ?>
            <?php $cur_date = $selected_date->getDay($n); ?>
            <?php $url = 'day/' . $cur_date->format('Ymd'); ?>
            <?= a_open($url) ?>
            <div class="day">
                <?= $today == $cur_date ? 'Today' : $cur_date->format('D') ?>
            </div>
            <div class="date"><?= $cur_date->format('j') ?></div>
            <?= a_close() ?>
            <?php endfor; ?>
        </div>
    </div>

    <a href="/" class="next">Next</a>
</div>
