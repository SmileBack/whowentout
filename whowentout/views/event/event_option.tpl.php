<?php
/* @var $invite_engine InviteEngine */
$invite_engine = build('invite_engine');
$current_user = auth()->current_user();
$invite = $invite_engine->get_invite($event, $current_user);
?>

<label class="event_option all <?= $event->place->type ?>">
    <input type="radio"
           class="radio"
           name="event_id"
           value="<?= $event->id ?>"
           <?= $selected ? 'checked="checked"' : '' ?> />
    
    <div class="place">
        <?= $event->name ?>
        <?php if ($invite): ?>
            <div class="invited_by">
                invited by <?= $invite->sender->first_name ?> <?= $invite->sender->last_name ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="deal">
        <?= $event->deal ?>
    </div>

    <div class="badge">
        <?php if ($selected && $event->deal): ?>
            <?= r::show_deal_link(array('event' => $event)) ?>
        <?php elseif ($selected && !$event->deal): ?>
            <div class="attending_badge pressed">attending</div>
        <?php else: ?>
            <div class="checkin_badge">check-in</div>
        <?php endif; ?>
    </div>

</label>
    