<?php $college = $user->college; ?>
<p class="user_command_notice">
    <?php if ($college->doors_are_closed()): ?>
        Come back after <?= $college->next_party_day_for($user)->format('l') ?> night's party to check in!
    <?php elseif ($user->has_checked_in()): ?>
        <?php $party = current_user()->get_checked_in_party(); ?>
        You have checked into <?= $party->place->name ?>.
        See below to go to the party gallery!
    <?php else: ?>
        <?php $party_day = $college->party_day(-1, TRUE); ?>
        Check in to the party you attended <em>last night</em> (<?= $party_day->format('l') ?>)!
    <?php endif; ?>
</p>
    