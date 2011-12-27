<?php
/* @var $invite_engine InviteEngine */
$invite_engine = factory()->build('invite_engine');

/* @var $checkin_engine CheckinEngine */
$checkin_engine = factory()->build('checkin_engine');

$current_user = auth()->current_user();
$friends = $current_user->friends->where('networks.name', 'Stanford')
                                 ->order_by('first_name');
?>

<form class="event_invite" method="post" action="/invites/create">
    <?= a(app()->event_link($event, array('class' => 'event_link')), 'Back to Event') ?>

    <input type="hidden" name="event_id" value="<?= $event->id ?>" />
    <fieldset>
        <legend>
            <h1>Invite your friends to the party.</h1>
        </legend>
        <ul>
            <?php foreach ($friends as $friend): ?>
                <li>
                    <label>
                        <?= r::user_thumb(array('user' => $friend)) ?>

                        <div class="invite_status">
                            <?php if ($invite_engine->invite_is_sent($event, $current_user, $friend)): ?>
                                invited by you
                            <?php elseif ($invite_engine->is_invited($event, $friend)): ?>
                                invited
                            <?php elseif ($checkin_engine->user_has_checked_into_event($friend, $event)): ?>
                                attending
                            <?php else: ?>
                            &nbsp;
                            <?php endif; ?>
                        </div>

                        <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" />
                        <?= $friend->first_name ?>
                        <?= substr($friend->last_name, 0, 1) ?>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
    <fieldset>
        <input type="submit" class="send_invites_button" value="Send Invites" />
    </fieldset>
</form>