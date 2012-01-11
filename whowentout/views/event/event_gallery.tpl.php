<?php
/**
 * @var $date DateTime
 * @var $user DatabaseRow
 * @var $checkin DatabaseRow|null the checkin the user has for the date (if any)
 * @var $checkins DatabaseRow[]
 */
?>
<div class="checkin_gallery">

    <?php if ($checkin): ?>
        <?= r::event_invite_link(array('event' => $checkin->event)) ?>
    <?php else: ?>
        <img class="checkin_gallery_message" src="/images/checkin_gallery_message.png" align="checkin to see who's going out" />
    <?php endif; ?>

    <ul>

        <?php if ($hidden): ?>
            <?php for ($n = 0; $n < 4 * 3; $n++): ?>
                <li>
                    <?= r::profile_anonymous() ?>
                </li>
            <?php endfor; ?>

        <?php else: ?>
            <?php foreach ($checkins as $checkin): ?>
                <li>
                    <?= r::profile_small(array('user' => $checkin->user, 'hidden' => false)) ?>
                    <div>attending <?= $checkin->event->name ?></div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>

    </ul>
</div>
