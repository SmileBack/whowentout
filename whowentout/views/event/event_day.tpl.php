<div class="event_day" data-date="<?= $date->format('Ymd') ?>">

    <?= r::event_list(array('date' => $date, 'selected_event' => $checkin_event, 'current_user' => $current_user)) ?>

    <?php if (browser::is_desktop() && InviteContest::is_contest_date($date)): ?>
        <?= r::contest_link(array('date' => $date)); ?>
    <?php endif; ?>

    <div class="event_gallery"></div>
    
</div>
