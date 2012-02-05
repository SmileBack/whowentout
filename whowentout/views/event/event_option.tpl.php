<?php
$current_user = auth()->current_user();

/* @var $invite_engine InviteEngine */
$invite_engine = build('invite_engine');

/* @var $checkin_engine CheckinEngine */
$checkin_engine = build('checkin_engine');

$invite = $invite_engine->get_invite($event, $current_user);

$checkin_count = $checkin_engine->get_checkin_count($event);
?>

<label class="event_option all <?= $event->place->type ?>">
    <input type="radio"
           class="radio"
           name="event_id"
           value="<?= $event->id ?>"
           <?= $is_selected ? 'checked="checked"' : '' ?> />
    
    <div class="place">
        <?= $event->name ?>

        <?php if ($selected_event && $checkin_count > 0): ?>
            <span>(<?= $checkin_count ?>)</span>
        <?php endif; ?>

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
        <?php if ($is_selected && $event->deal): ?>
            <?= r::show_deal_link(array('event' => $event)) ?>
        <?php elseif ($is_selected && !$event->deal): ?>
            <div class="attending_badge pressed">attending</div>
        <?php else: ?>
            <div class="checkin_badge">check-in</div>
        <?php endif; ?>
    </div>

</label>
    