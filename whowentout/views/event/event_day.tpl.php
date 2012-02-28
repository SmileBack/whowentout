<div class="event_day" data-date="<?= $date->format('Ymd') ?>">


    <?= r::event_picker(array(
            'date' => $date,
            'selected_event' => $checkin_event,
            'user' => $current_user
        ))
    ?>

    <?= r::event_day_summary(array(
        'user' => $current_user,
        'date' => $date,
    )) ?>

</div>
