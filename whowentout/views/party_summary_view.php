<?php $date = new DateTime($party->date, $party->college->timezone); ?>

<div class="party_summary">
    <h2>
        <a href="<?= "party/$party->id" ?>">
            <?= $party->college->format_time($date) ?> &nbsp; | &nbsp;  <?= $party->place->name ?> Attendees
        </a>
    </h2>
    <div class="body">
        <div class="left">
            <?= load_view('recent_attendees_view', array('party' => $party, 'count' => 4)) ?>
        </div>
        <div class="right">
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
</div>
    