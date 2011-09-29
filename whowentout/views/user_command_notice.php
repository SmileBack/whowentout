<?php $college = $user->college; ?>
<p class="user_command_notice">
    <?php if ($college->doors_are_closed()): ?>
        Come back after <?= $college->next_checkin_day_for($user)->format('l') ?> night's party to check in!
    <?php elseif ($user->has_checked_in()): ?>
        <?php $party = current_user()->get_checked_in_party(); ?>
        You have checked into <?= $party->place->name ?>.
        <?= anchor("party/$party->id", 'See below', array('class' => 'show_spotlight', 'data-target' => '#party_summary_' . $party->id, 'data-delay' => 1000)) ?>
        to go to the party gallery!
    <?php else: ?>
        <?php $party_day = $college->party_day(-1, TRUE); ?>
        Check in to the party you attended last night (<?= $party_day->format('l') ?>)!
    <?php endif; ?>
</p>
    