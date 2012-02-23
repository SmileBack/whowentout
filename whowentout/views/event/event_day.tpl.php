<div class="event_day" data-date="<?= $date->format('Ymd') ?>">

    <?php benchmark::start('event_list'); ?>
    <?= r::event_list(array('date' => $date, 'selected_event' => $checkin_event, 'current_user' => $current_user)) ?>
    <?php benchmark::end('event_list'); ?>

    <?php if (browser::is_desktop() && InviteContest::is_contest_date($date)): ?>
        <?= r::contest_link(array('date' => $date)); ?>
    <?php endif; ?>

    <?php benchmark::start('event_gallery'); ?>
    <?=
    r::event_gallery(array(
        'date' => $date,
        'user' => $current_user,
    ))
    ?>
    <?php benchmark::end('event_gallery'); ?>

</div>
