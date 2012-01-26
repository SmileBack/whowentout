<?php
/* @var $checkin_engine CheckinEngine */
$checkin_engine = build('checkin_engine');
$checkins = $checkin_engine->get_checkins_for_user($user);
?>
<ul class="profile_checkins">
    <?php foreach ($checkins as $checkin): ?>
        <?php $link = app()->event_link($checkin->event); ?>
        <li>
            <h4><?= $checkin->event->date->format('Y-m-d') ?></h4>
            <?= a($link, $checkin->event->name) ?>
        </li>
    <?php endforeach; ?>
</ul>
