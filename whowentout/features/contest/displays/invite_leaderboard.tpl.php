<?php
/* @var $board InviteLeaderboard */
$board = build('invite_leaderboard');
$items = $board->get_items($date);
?>
<div class="invite_leaderboard">
    <div class="contest_instructions">
        <h1>
            <p class="first">The one who invites the most friends</p>
            <p class="second">to check-in for Thursday night</p>
            <p class="third">wins a $50 bar tab!</p>
        </h1>

        <h3>Rules:</h3>
        <ol>
            <li>To send an invite, first check-in to a party</li>
            <li>For an invite to count, the invited user must check-in to the party he was invited to</li>
            <li>The winner will be announced by 9pm on Thursday night</li>
        </ol>
    </div>
<?php if (count($items) > 0): ?>
    <div class="current_rankings">
        <h1>Current Rankings</h1>
        <ol>
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
    </div>
<?php else: ?>
    <h2>Come back later!</h2>
<?php endif; ?>
</div>