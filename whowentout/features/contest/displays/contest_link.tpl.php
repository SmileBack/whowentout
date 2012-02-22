<?php
/* @var $board InviteLeaderboard */
$board = build('invite_leaderboard');
$leader = $board->get_leader($date);
?>
<h2 class="contest_link">

    <?= a('leaderboard/' . $date->format('Y/m/d'), '$50 bar tab competition', array('class' => 'view_leaderboard')) ?>
    <?php if ($leader): ?>
    <p>
        <span>Current Leader:</span>
        <span><?= $leader->user->first_name . ' ' . $leader->user->last_name ?></span>
    </p>
    <?php endif; ?>

</h2>