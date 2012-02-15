<?php
$current_user = auth()->current_user();

/* @var $invite_engine InviteEngine */
$invite_engine = build('invite_engine');

/* @var $checkin_engine CheckinEngine */
$checkin_engine = build('checkin_engine');

benchmark::start('get_invite');
$invite = $invite_engine->get_invite($event, $current_user);
benchmark::end('get_invite');

?>

<div class="event_option all <?= $event->place->type ?>">
    <div class="place">
        <?= $event->name ?>

        <?php if ($invite): ?>
            <div class="invited_by">
                invited by <?= $invite->sender->first_name ?> <?= $invite->sender->last_name ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="deal">
        <ul class="expandable">
           <?php foreach (explode("\n", $event->deal) as $line): ?>
                <li><?= $line ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="badge">
        <?php if ($is_selected && browser::is_desktop()): ?>
        <?= r::event_invite_link(array('event' => $event)) ?>
        <?php endif; ?>

        <?php if ($is_selected && $event->deal): ?>
            <?= r::show_deal_link(array('event' => $event)) ?>
        <?php elseif ($is_selected && !$event->deal): ?>
            <div class="attending_badge pressed">attending</div>
        <?php else: ?>
            <form method="post" action="/checkin">
                <input type="hidden" name="event_id" value="<?= $event->id ?>" />
                <input type="submit" class="checkin_badge" value="check-in" />
            </form>
        <?php endif; ?>
    </div>

</div>
    