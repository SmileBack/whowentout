<?= r::events_date_selector() ?>

<?= r::event_list(array('date' => $date, 'selected_event' => $selected_event)) ?>

<?php if ($selected_event): ?>
    <?= r::event_invite_link(array('event' => $selected_event)) ?>
    <?= r::checkin_gallery(array('event' => $selected_event)) ?>
<?php endif; ?>
