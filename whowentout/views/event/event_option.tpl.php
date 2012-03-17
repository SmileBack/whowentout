<?php
$current_user = auth()->current_user();

/* @var $invite_engine InviteEngine */
$invite_engine = build('invite_engine');

/* @var $checkin_engine CheckinEngine */
$checkin_engine = build('checkin_engine');

benchmark::start('get_invite');
$invite_senders = $invite_engine->get_invite_senders($event, $current_user);
benchmark::end('get_invite');

$data = array(
    'template' => 'event-option',
    'event' => to::json($event),
    'is_selected' => $is_selected,
    'selected_event' => to::json($selected_event),
    'invite_senders' => to::json($invite_senders),
);
?>

<?= html_element_open('div', array('class' => 'event_option render'), $data) ?>

<?php if (false): ?>
    <div class="place">
        <?= $event->name ?>
        <?php if (false): ?>
        (<?= $event->place->type ?>)
        <?php endif; ?>

        <?php if (!empty($invite_senders)): ?>
            <div class="invited_by">
                invited by <?= format::people($invite_senders, 2) ?>
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
        <?php if ($is_selected): ?>
            <div class="attending_badge pressed">attending</div>
        <?php else: ?>
            <form method="post" action="/checkin">
                <input type="hidden" name="event_id" value="<?= $event->id ?>" />

                <?php if ($selected_event): ?>
                    <input type="submit" class="checkin_badge" value="switch" />
                <?php else: ?>
                    <input type="submit" class="checkin_badge" value="join" />
                <?php endif; ?>

            </form>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?= html_element_close('div') ?>

