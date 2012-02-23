<?php
/* @var $event */
/* @var $invite_engine InviteEngine */
/* @var $checkin_engine CheckinEngine */
/* @var $friends */
/* @var $event */
/* @var $current_user */
?>
<form class="event_invite invite" method="post" action="/events/<?= $event->id ?>/invite/submit">
    <input type="hidden" name="event_id" value="<?= $event->id ?>" />

    <fieldset>

    <input type="text" class="search inline_label" title="Type a friend's name" />

    <ul>
        <?php foreach ($friends as $friend): ?>
            <?php
            $invite_is_sent = $invite_engine->invite_is_sent($event, $current_user, $friend);
            $is_invited = $invite_engine->is_invited($event, $friend);
            $user_has_checked_in = $checkin_engine->user_has_checked_into_event($friend, $event);
            ?>
            <li>
                <label>
                    <?= r::user_thumb(array('user' => $friend)) ?>

                    <?php if ($user_has_checked_in): //attending event ?>
                        <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" checked="checked" disabled="disabled" />
                        <div class="note">attending</div>
                    <?php elseif ($invite_is_sent): //invited by you ?>
                        <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" checked="checked" disabled="disabled" />
                        <div class="note">invited</div>
                    <?php elseif ($is_invited): //invited by someone else ?>
                        <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" />
                        <div class="note">invited</div>
                    <?php else: ?>
                        <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" />
                    <?php endif; ?>

                    <div class="user_first_name">
                        <?= $friend->first_name ?>
                    </div>

                    <div class="user_last_name">
                        <?= $friend->last_name ?>
                    </div>

                    <?= r::profile_networks(array('user' => $friend)) ?>

                </label>
            </li>
        <?php endforeach; ?>
    </ul>
    </fieldset>

    <fieldset class="buttons">
        <input type="submit" class="send_invites_button" name="send" value="Send Invites" />
        <?= a(app()->event_link($event), 'Skip', array('class' => 'cancel_link')) ?>
    </fieldset>

</form>
