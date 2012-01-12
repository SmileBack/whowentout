<div class="event_day" data-date="<?= $date->format('Ymd') ?>">
    <?= r::date_tip(array('date' => $date)) ?>

    <?= r::event_list(array('date' => $date, 'selected_event' => $checkin_event)) ?>

    <?=
    r::event_gallery(array(
        'date' => $date,
        'user' => $current_user,
    ))
    ?>
</div>
