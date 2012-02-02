<?php
/* @var $entourage_engine EntourageEngine */
/* @var $friends */
/* @var $current_user */
?>
<form class="event_invite invite" method="post" action="/entourage/invite/submit">

    <fieldset>
        <input type="text" class="search inline_label" title="Type a friend's name" />

        <ul>
            <?php foreach ($friends as $friend): ?>
                <?php
                $in_entourage = $entourage_engine->in_entourage($current_user, $friend);
                $request_was_sent = $entourage_engine->request_was_sent($current_user, $friend);
                ?>
                <li>
                    <label>
                        <?= r::user_thumb(array('user' => $friend)) ?>

                        <?php if ($in_entourage): ?>
                            <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" disabled="disabled" />
                            <div class="note">in entourage</div>
                        <?php elseif ($request_was_sent): ?>
                            <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" />
                            <div class="note">sent request</div>
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

    <fieldset class="buttons">
        <input type="submit" class="send_invites_button" name="send" value="Send Requests" />
        <?= a(app()->profile_link(auth()->current_user()), 'Cancel', array('class' => 'cancel_link')) ?>
    </fieldset>

</form>
