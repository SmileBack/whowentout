<?php
/* @var $party_group PartyGroup */
$party_group;
/* @var $user XUser */
$user;
?>

<?php
$selected_party = $party_group->get_selected_party($user);
$user_phase = $party_group->get_user_phase($user);
?>

<div class="party_group_badge <?= strtolower($user_phase) ?>">
    <?php if ($user_phase == PartyGroupPhase::Checkin): ?>
        check in!
    <?php elseif ($user_phase == PartyGroupPhase::Attending): ?>
        attending
    <?php elseif ($user_phase == PartyGroupPhase::Attended): ?>
        attended
    <?php elseif ($user_phase == PartyGroupPhase::CheckinsClosed): ?>
        check-ins closed
    <?php endif; ?>
</div>
