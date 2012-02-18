<div class="event_day" data-date="<?= $date->format('Ymd') ?>">

    <?php benchmark::start('event_list'); ?>
    <?= r::event_list(array('date' => $date, 'selected_event' => $checkin_event, 'current_user' => $current_user)) ?>
    <?php benchmark::end('event_list'); ?>

    <?php benchmark::start('event_gallery'); ?>
    <?=
    r::event_gallery(array(
        'date' => $date,
        'user' => $current_user,
    ))
    ?>
    <?php benchmark::end('event_gallery'); ?>

</div>
