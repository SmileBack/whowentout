<?php
    /* @var $checkin_engine CheckinEngine */
$checkin_engine = factory()->build('checkin_engine');
$checkins = $checkin_engine->get_checkins_for_event($event);
?>

<div class="checkin_gallery">
    <h1><?= $event->name ?>'s Attendees</h1>
    <ul>
        <?php foreach ($checkins as $checkin): ?>
        <li>
            <?= r::profile_small(array('user' => $checkin->user)) ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>