<div class="upcoming_parties">
    <?php for ($offset = -1; $offset <= 4; $offset++): ?>
    <?php
    $day = college()->day($offset, TRUE);
    $is_party_day = college()->is_party_day($day);
    ?>
    <div class="day_summary" data-day="<?= $day->format('Y-m-d') ?>">
        <h3><?= $college->format_time($day, 'short') ?></h3>
        <div class="inner">
            <div class="party_list">
                <?php if ($is_party_day): ?>
                <?php $parties = $college->parties_on($day); ?>
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
                No Parties
                <?php endif; ?>
            </div>
            
        </div>
    </div>
    <?php endfor; ?>
</div>
