<?php
/* @var $board InviteLeaderboard */
$board = build('invite_leaderboard');
$items = $board->get_items($date);
?>
<ol class="invite_leaderboard">
    <?php foreach ($items as $item): ?>
    <li>

        <h2>
            <span class="user"><?= $item->user->first_name . ' ' . $item->user->last_name ?></span>
            (<a class="score" href="#score"><?= $item->score ?></a>)
        </h2>

        <ul class="people" style="display: none;">
            <?php foreach ($item->invites as $invite): ?>
                <li>
                    <span>
                        <?= $invite->receiver->first_name . ' ' . $invite->receiver->last_name ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>

    </li>
    <?php endforeach; ?>
</ol>
