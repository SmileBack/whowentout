<?php
 $yesterday = college()->yesterday(TRUE)
?>

<?php if (empty($parties_attended)): ?>
<h2>The parties you checkin to will appear here</h2>
<?php else: ?>
<div class="parties_attended">
    
    <?php if (!current_user()->has_attended_party_on_date($yesterday)): ?>
    <div class="party_summary">
        <h2>
            <?= college()->format_time($yesterday) ?>
            |
            <?= load_view('forms/checkin_form') ?>
        </h2>
        <div class="body">
            <?= load_view('recent_attendees_view', array('count' => 4)) ?>
        </div>
    </div>
    <?php endif; ?>

    <?php foreach ( current_user()->recently_attended_parties() as $party ): ?>
    <?php $date = new DateTime($party->date, $party->college->timezone); ?>
    <div class="party_summary">
        <h2>
            <a href="<?= "party/$party->id" ?>">
                <?= college()->format_time($date) ?> &nbsp; | &nbsp;  <?= $party->place->name ?> Attendees
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

    <?php endforeach; ?>
</div>
<?php endif; ?>
