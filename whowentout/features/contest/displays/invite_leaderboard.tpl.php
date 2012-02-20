<?php
/* @var $board InviteLeaderboard */
$board = build('invite_leaderboard');
$items = $board->get_items($date);
?>
<ul class="invite_leaderboard">
    <?php foreach ($items as $item): ?>
    <li>
        <span class="user"><?= $item->user->first_name . ' ' . $item->user->last_name ?></span>
        <span class="score">(<?= $item->score ?>)</span>
    </li>
    <?php endforeach; ?>
</ul>
