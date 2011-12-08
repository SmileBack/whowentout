<?php $date = new DateTime('now') ?>

<div id="dates">
    <a href="/" class="prev">Prev</a>
    <?php for ($n = 0; $n < 6; $n++): ?>
        <?php $url = url('events/index/' . $date->format('Ymd')); ?>
        <?= a_open('events/index/' . $date->format('Ymd')) ?>
            <div class="day">
                <?= $n == 0 ? 'Today' : $date->format('D') ?>
            </div>
            <div class="date"><?= $date->format('j') ?></div>
        <?= a_close() ?>
        <?php $date->modify('+1 day') ?>
    <?php endfor; ?>
    <a href="/" class="next">Next</a>
</div>