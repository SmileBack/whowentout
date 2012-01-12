<div class="event_day" data-date="<?= $date->format('Ymd') ?>">
    <h2 style="width: 100%; text-align: center;"><?= $date->format('D, d M Y') ?></h2>

    <?= r::event_list(array('date' => $date, 'selected_event' => $checkin_event)) ?>

    <?=
    r::event_gallery(array(
        'date' => $date,
        'user' => $current_user,
    ))
    ?>
</div>
