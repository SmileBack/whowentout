<?php

//figure out the next time you should check in
$college = college();

$doors_open = FALSE;
$within_checkin_periods = $college->within_checkin_periods();

$checkin_day = $college->day_of_type('checkin', 1, TRUE);

$party_day = clone $checkin_day;
$party_day->modify('-1 day');

$doors_opening_time = $college->get_opening_time(TRUE, $checkin_day);
$doors_closing_time = $college->get_closing_time(TRUE, $checkin_day);

$open_parties = $college->open_parties($doors_opening_time);

?>

<div class="party_summary upcoming" data-party-date="<?= $party_day->format('Y-m-d') ?>">

            <h2>
                <?= form_open('checkin', array(
                                  'class' => 'checkin_form',
                                  'doors_opening_time' => $doors_opening_time->getTimestamp(),
                                  'doors_closing_time' => $doors_closing_time->getTimestamp(),
                                  'doors_open' => (int)$doors_open,
                             )) ?>
                    <?= $college->format_time($party_day) ?>
                    |
                    <?= parties_dropdown($open_parties) ?>
                    <button type="submit" class="submit_button">check in</button>
                <?= form_close() ?>
            </h2>

            <div class="badge">upcoming</div>

            <div class="body">
                <?= load_view('recent_attendees_view', array('count' => 4, 'party' => NULL)) ?>
                <div class="countdown">
                    <h2>Doors open for check in on <?= $doors_opening_time->format('l \a\t g a') ?></h2>
                    <div class="body">
                        <div class="remaining_time time_until"
                             data-time="<?= $doors_opening_time->getTimestamp() ?>"></div>
                    </div>
                </div>
            </div>

</div>
