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
    <?php endif; ?>

    <ul>
        <?php foreach ($checkins as $checkin): ?>
        <li>
            <?= r::profile_small(array('user' => $checkin->user, 'hidden' => false)) ?>
            <div>attending <?= $checkin->event->name ?></div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
