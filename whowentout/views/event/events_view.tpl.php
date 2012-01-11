<?= r::events_date_selector(array('selected_date' => $date)) ?>

<?= r::event_list(array('date' => $date, 'selected_event' => $selected_event)) ?>

<?php if ($selected_event): ?>
    <?=
    r::event_gallery(array(
        'date' => $date,
        'user' => auth()->current_user(),
    ))
    ?>
<?php endif; ?>
