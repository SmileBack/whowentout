<?php
$checkin_state = new UserCheckinState( current_user() );
?>
<p class="user_command_notice">
    <?php if ($checkin_state->door_is_open() && ! $checkin_state->user_has_checked_in()): ?>
        Check in to the party you attended <?= $checkin_state->get_party_day()->getDayOfWeek() ?> night!
    <?php elseif ($checkin_state->door_is_open() && $checkin_state->user_has_checked_in()): ?>
        See below to go to the <?= $checkin_state->get_checked_in_party()->place->name ?> gallery!
    <?php else: ?>
        Come back after <?= $checkin_state->get_next_party_day()->getDayOfWeek() ?>'s party to check in!
    <?php endif; ?>
</p>
    