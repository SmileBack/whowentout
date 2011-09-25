<?php

//figure out the next time you should check in
$college = college();
$checkin_day = $college->today();
$doors_open = $college->doors_are_open();

if ((logged_in() && $user->has_checked_in_on_date($checkin_day))
    || !$college->within_checkin_periods()) {
    $checkin_day = $college->day_of_type('checkin', 1, FALSE, $checkin_day);
    $doors_open = FALSE;
}

$open_parties = $college->open_parties($checkin_day);
$doors_opening_time = $college->get_opening_time(TRUE, $checkin_day);
$doors_closing_time = $college->get_closing_time(TRUE, $checkin_day);

if (logged_in())
    $party = $user->get_checked_in_party($checkin_day);
else
    $party = NULL;

$party_day = $checkin_day->modify('-1 day');

?>

<div class="party_summary" data-party-date="<?= $party_day->format('Y-m-d') ?>">
    
    <?= form_open('checkin', array(
                              'id' => 'checkin_form',
                              'doors_opening_time' => $doors_opening_time->getTimestamp(),
                              'doors_closing_time' => $doors_closing_time->getTimestamp(),
                              'doors_open' => (int)$doors_open,
                         )) ?>

    <h2>
        <?= $college->format_time($party_day) ?>
        |
        <?= parties_dropdown($open_parties) ?>
        <button type="submit" class="submit_button">check in</button>

        <?php if ($doors_open): ?>
            <span class="doors_message">
                Doors will close for check-in at
                    <?= $doors_closing_time->format('g a') ?>
                    ( in <span class="remaining_time time_until" data-time="<?= $doors_closing_time->getTimestamp() ?>"></span> )
            </span>
        <?php else: ?>
            <span class="doors_message">
                Doors will open for checkin-in at
                    <?= $doors_opening_time->format('g a') ?>
                    ( in <span class="remaining_time time_until" data-time="<?= $doors_opening_time->getTimestamp() ?>"></span> )
            </span>
        <?php endif; ?>

    </h2>

    <div class="body">
        <?= load_view('recent_attendees_view', array('count' => 4, 'party' => $party)) ?>
    </div>

    <?= form_close() ?>

</div>
