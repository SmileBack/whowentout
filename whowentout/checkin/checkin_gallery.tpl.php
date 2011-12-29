<?php
/* @var $checkin_engine CheckinEngine */
$checkin_engine = factory()->build('checkin_engine');
$checkins = db()->table('checkins')
                ->where('event.date', $event->date)
                ->order_by('event.name');
?>

<div class="checkin_gallery">
    <ul>
        <?php foreach ($checkins as $checkin): ?>
        <li>
            <?= r::profile_small(array('user' => $checkin->user)) ?>
            <div>attended <?= $checkin->event->name ?></div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
