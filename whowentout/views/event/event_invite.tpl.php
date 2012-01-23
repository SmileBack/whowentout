<form class="event_invite" method="post" action="/events/<?= $event->id ?>/invite/submit">
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

                        <?php if ($invite_engine->invite_is_sent($event, $current_user, $friend)): //invited by you ?>
                            <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" checked="checked" disabled="disabled" />
                        <?php elseif ($invite_engine->is_invited($event, $friend)): //invited by someone else ?>
                            <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" checked="checked" disabled="disabled" />
                        <?php elseif ($checkin_engine->user_has_checked_into_event($friend, $event)): //attending event ?>
                            <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" checked="checked" disabled="disabled" />
                        <?php else: ?>
                            <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" />
                        <?php endif; ?>

                        <div class="user_first_name">
                            <?= $friend->first_name ?>
                        </div>
                        <div class="user_last_name">
                            <?= $friend->last_name ?>
                        </div>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>

    <fieldset>
        <input type="submit" class="send_invites_button" name="send" value="Send Invites" />
        <?= a(app()->event_link($event), 'Skip', array('class' => 'cancel_link')) ?>
    </fieldset>

</form>