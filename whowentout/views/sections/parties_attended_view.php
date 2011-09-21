<?php if (empty($parties_attended)): ?>
<h2>The parties you checkin to will appear here</h2>
<?php else: ?>
<div class="parties_attended">
    <?php foreach ( current_user()->recently_attended_parties() as $party ): ?>
    <?php $date = new DateTime($party->date, $party->college->timezone); ?>
    <div class="party_summary">
        <h2>
            <p class="date"><?= $date->format("l, M. jS"); ?></p>
            <?php if ($party): ?>
                <div class="divider">|</div>
                <div class="place"><?= anchor("party/{$party->id}", $party->place->name); ?></div>
            <?php endif; ?>
        </h2>
        <div class="body">
            <?php if ($party): ?>
                <?=
                    load_view('party_notices_view', array(
                                                         'user' => $user,
                                                         'party' => $party,
                                                    )); ?>
            <?php else: ?>
                <h2 class="place">&nbsp;</h2>
            <?php endif; ?>
        </div>
    </div>

    <?php endforeach; ?>
</div>
<?php endif; ?>
