<?php
/* @var $checkin_engine CheckinEngine */
$checkin_engine = build('checkin_engine');
$checkins = $checkin_engine->get_checkins_for_user($user);
?>
<?php if (count($checkins) > 0): ?>
<ul class="profile_checkins">
    <?php foreach ($checkins as $checkin): ?>
        <?php $link = app()->event_link($checkin->event); ?>
        <li>
            <?= r::date(array('date' => $checkin->event->date)); ?>
            <span class="event"><?= a($link, $checkin->event->name) ?></span>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
    <h2><?= $user->first_name ?> hasn't checked into any parties.</h2>
<?php endif; ?>