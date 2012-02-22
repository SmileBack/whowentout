<div class="event_day" data-date="<?= $date->format('Ymd') ?>">

    <?php if (InviteLeaderboard::is_contest_date($date)): ?>
    <h2 style="text-align: center;">
    <?= a('leaderboard/' . $date->format('Y/m/d'), '$50 bar tab competition', array('class' => 'view_leaderboard')) ?>
    </h2>
    <?php endif; ?>

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
