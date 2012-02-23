<?php
/* @var $contest InviteContest */
$contest = build('invite_contest', $date);
$leader = $contest->get_leader();
?>

<h2 class="contest_link">

    <?= a('leaderboard/' . $date->format('Y/m/d'), '$50 bar tab competition', array('class' => 'view_leaderboard')) ?>
    <?php if ($leader): ?>
    <p>
        <span><?= $contest->has_ended() ? 'Winner' : 'Current Leader' ?>:</span>
        <span><?= $leader->user->first_name . ' ' . $leader->user->last_name ?></span>
    </p>
    <?php endif; ?>

</h2>
