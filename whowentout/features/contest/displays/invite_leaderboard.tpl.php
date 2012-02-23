<?php
/* @var $contest InviteContest */
$contest = build('invite_contest', $date);

$items = $contest->get_items($date);
$leader = $contest->get_leader();

$eligible_events = $contest->get_eligible_events()->collect('name');
?>

<div class="invite_leaderboard">
    <div class="contest_instructions">

        <h1>We are giving away a <em>$50 bar tab</em> every Thursday, Friday, and Saturday!</h1>

        <h2>
            To win <?= $date->format('l') ?>'s bar tab,
            just invite the most friends to check in for <?= $date->format('l') ?> night!
        </h2>

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
    <div class="current_rankings">
    <?php if (count($items) > 0): ?>

            <?php if ($contest->has_ended()): ?>
                <h1>Final Ranking</h1>
            <?php else: ?>
                <h1>Current Ranking</h1>
            <?php endif; ?>

            <ol>
                <?php foreach ($items as $item): ?>
                <li>

                    <h2>
                        <span class="user"><?= $item->user->first_name . ' ' . $item->user->last_name ?></span>
                        (<a class="score" href="#score"><?= $item->score ?></a>)
                        <?php if ($contest->has_ended() && $item->user == $leader->user): ?>
                            <span> - Winner</span>
                        <?php endif; ?>
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
    <?php else: ?>
        <h2>Come back later to see the rankings!</h2>
    <?php endif; ?>
    </div>
</div>