<?php
/* @var $event */
/* @var $invite_engine InviteEngine */
/* @var $checkin_engine CheckinEngine */
/* @var $friends */
/* @var $event */
/* @var $current_user */
?>
<form class="event_invite" method="post" action="/events/<?= $event->id ?>/invite/submit">
    <input type="hidden" name="event_id" value="<?= $event->id ?>" />

    <fieldset>
    <ul>
        <?php foreach ($friends as $friend): ?>
            <li>
                <?php benchmark::start('item'); ?>
                <label>
                    <?php benchmark::start('user_thumb'); ?>
                    <?= r::user_thumb(array('user' => $friend)) ?>
                    <?php benchmark::end('user_thumb'); ?>

                    <?php
                    benchmark::start('invite_is_sent');
                    $invite_is_sent = $invite_engine->invite_is_sent($event, $current_user, $friend);
                    benchmark::end('invite_is_sent');

                    benchmark::start('is_invited');
                    $is_invited = $invite_engine->is_invited($event, $friend);
                    benchmark::end('is_invited');

                    benchmark::start('user_has_checked_in');
                    $user_has_checked_in = $checkin_engine->user_has_checked_into_event($friend, $event);
                    benchmark::end('user_has_checked_in');
                    ?>

                    <?php benchmark::start('checkboxes'); ?>
                    <?php if ($invite_is_sent): //invited by you ?>
                        <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" checked="checked" disabled="disabled" />
                    <?php elseif ($is_invited): //invited by someone else ?>
                        <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" checked="checked" disabled="disabled" />
                    <?php elseif ($user_has_checked_in): //attending event ?>
                        <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" checked="checked" disabled="disabled" />
                    <?php else: ?>
                        <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" />
                    <?php endif; ?>
                    <?php benchmark::end('checkboxes'); ?>

                    <div class="user_first_name">
                        <?= $friend->first_name ?>
                    </div>

                    <div class="user_last_name">
                        <?= $friend->last_name ?>
                    </div>

                </label>
                <?php benchmark::end('item'); ?>
            </li>
        <?php endforeach; ?>
    </ul>
    </fieldset>

    <fieldset class="buttons">
        <input type="submit" class="send_invites_button" name="send" value="Send Invites" />
        <?= a(app()->event_link($event), 'Skip', array('class' => 'cancel_link')) ?>
    </fieldset>

    <?php krumo::dump(benchmark::summary()); ?>

</form>