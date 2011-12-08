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

<?= r::event_list(array('date' => $date)) ?>

<?= r::event_gallery() ?>
