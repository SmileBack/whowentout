<?= r::events_date_selector() ?>

<?php
$events = array(
    array('name' => 'Rhino Bar', 'deal' => 'Free cover before 11 pm'),
    array('name' => 'The Guards', 'deal' => '$3 Drafts before midnight'),
    array('name' => 'Shadowroom', 'deal' => '$8 Pitchers before midnight'),
    array('name' => 'Public', 'deal' => '$2.50 Coronas before midnight'),
    array('name' => 'Madhatters', 'deal' => '$2 Test tube shooters'),
    array('name' => 'Eden', 'deal' => 'Buy 1 drink, get 2nd one free'),
);
?>

<fieldset class="event_list">
    <legend>Check-in to claim your deal and see who else is going!</legend>
    <ul>
    <?php foreach ($events as $k => $event): ?>
        <li class="<?= $k == 0 ? 'first' : '' ?>">
            <?= r::event_option($event) ?>
        </li>
    <?php endforeach; ?>
    </ul>
</fieldset>

<?= r::event_gallery() ?>
