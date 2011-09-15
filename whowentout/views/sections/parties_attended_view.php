<?php if (empty($parties_attended)): ?>
<h2>The parties you checkin to will appear here</h2>
<?php else: ?>
<div class="parties_attended">
    <?php foreach (array(0, 1, 2) as $offset): ?>
    <?php
            $date = college()->this_week_party_day($offset);
    $party = $user->get_attended_party($date);
    ?>

    <div class="party_summary">
        <h2 class="date">
            <p class="date"><?= $date->format("l, M. jS"); ?></p>

            <div class="divider" style="display: none;">|</div>
        </h2>
        <div class="body">
            <?php if ($party): ?>
                <h2 class="place"><?= anchor("party/{$party->id}", $party->place->name); ?></h2>
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
