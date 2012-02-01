<?php
/* @var $entourage_engine EntourageEngine */
/* @var $friends */
/* @var $current_user */
?>
<form class="event_invite invite" method="post" action="/entourage/invite">

    <fieldset>
        <input type="text" class="search inline_label" title="Type a friend's name" />

        <ul>
            <?php foreach ($friends as $friend): ?>
                <li>
                    <label>
                        <?= r::user_thumb(array('user' => $friend)) ?>

                        <input type="checkbox" name="recipients[]" value="<?= $friend->id ?>" />

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
        <input type="submit" class="send_invites_button" name="send" value="Send Invites" />
        <?= a(app()->profile_link(auth()->current_user()), 'Cancel', array('class' => 'cancel_link')) ?>
    </fieldset>

</form>
