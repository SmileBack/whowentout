<h1><?= $event->name ?>'s Attendees (@ <?= $event->place->name ?>)</h1>
<?php
/* @var $checkin_engine CheckinEngine */
$checkin_engine = factory()->build('checkin_engine');
$checkins = $checkin_engine->get_checkins_for_event($event);
?>

<ul>
    <?php foreach ($checkins as $checkin): ?>
        <li>
            <?= $checkin->user->first_name . '  ' . $checkin->user->last_last ?>
        </li>
    <?php endforeach; ?>
</ul>
    