<?php $college = $user->college; ?>
<div class="upcoming_parties">
    <?php for ($offset = -1; $offset <= 4; $offset++): ?>
    <?php
    $day = $college->get_time()->getDay($offset);
    ?>
    <div class="day_summary" data-day="<?= $day->format('Y-m-d') ?>">
        <h3><?= $college->format_time($day, 'short') ?></h3>
        <div class="inner">
            <div class="party_list">
                <?php if ($day->isPartyDay()): ?>
                <?php $parties = $user->college->parties_on($day); ?>
                <?php if (empty($parties)): ?>
                    TBD
                    <?php else: ?>
                    <ul>
                        <?php foreach ($parties as $party): ?>
                        <li><?= $party->place->name ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                <?php else: ?>
                No Parties on <?= $day->format('l') ?>'s
                <?php endif; ?>
            </div>
            
        </div>
    </div>
    <?php endfor; ?>
</div>
