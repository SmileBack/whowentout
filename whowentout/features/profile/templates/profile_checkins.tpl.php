<?php
/* @var $checkin_engine CheckinEngine */
$checkin_engine = build('checkin_engine');
$checkins = $checkin_engine->get_checkins_for_user($user);
?>
<?php if (count($checkins) > 0): ?>
<div class="profile_checkins">
    <?php foreach ($checkins as $checkin): ?>
        <?php $link = app()->event_link($checkin->event); ?>
        <?= a_open($link, array('class' => 'checkin')); ?>
            <?= r::date(array('date' => $checkin->event->date)); ?>
            <span class="event"><?= $checkin->event->name ?></span>
        <?= a_close(); ?>
    <?php endforeach; ?>
</div>
<?php else: ?>
    <h2><?= ucfirst(format::first_name($user)) ?> <?= format::pov("hasn't", $user) ?> checked into any parties.</h2>
<?php endif; ?>