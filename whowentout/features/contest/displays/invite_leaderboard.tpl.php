<?php
/* @var $board InviteLeaderboard */
$board = build('invite_leaderboard');
$items = $board->get_items($date);

$eligible_events = db()->table('events')->where('date', $date)
                                        ->where('place.type', array('bar', 'club'))
                                        ->collect('name');

?>
<div class="invite_leaderboard">
    <div class="contest_instructions">
        <h1>The one who invites the most friends to check-in for <?= $date->format('l') ?> night <em>wins a $50 bar tab!</em>
        </h1>

        <div class="contest_rules">
        <h3>Rules:</h3>
        <ol>
            <li>To send an invite, first check-in to a party</li>
            <li>For an invite to count, the invited user must check-in to the party he was invited to</li>
            <li>The winner will be announced by 9pm on Thursday night</li>
            <?php if (count($eligible_events) > 0): ?>
                <li>Eligible places are <?= conjunct($eligible_events) ?>.</li>
            <?php endif; ?>
        </ol>
        </div>
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