<?php

//figure out the next time you should check in
$college = college();
$checkin_day = $college->today();
$doors_open = $college->doors_are_open();
$within_checkin_periods = $college->within_checkin_periods();
$open_parties = $college->open_parties($college->current_time());

$doors_opening_time = $college->get_opening_time(TRUE);
$doors_closing_time = $college->get_closing_time(TRUE);

if (logged_in())
    $party = $user->get_checked_in_party($checkin_day);
else
    $party = NULL;

$checked_in = ($party != NULL);

$party_day = clone $checkin_day;
$party_day->modify('-1 day');

$next_checkin_day = $college->day_of_type('checkin', 0, TRUE);
if (!$next_checkin_day || $checked_in)
    $next_checkin_day = $college->day_of_type('checkin', 1, TRUE);

$next_party_day = clone $next_checkin_day;
$next_party_day->modify('-1 day');

$next_doors_opening_time = $college->get_opening_time(TRUE, $next_checkin_day);
$next_doors_closing_time = $college->get_closing_time(TRUE, $next_checkin_day);

?>

<div class="party_summary checkin" data-party-date="<?= $party_day->format('Y-m-d') ?>">


    <?php if ($doors_open && !$checked_in): ?>
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

    <div class="badge">open for checkin</div>

    <div class="body">
        <?= load_view('recent_attendees_view', array('count' => 4, 'party' => $party)) ?>
        <div class="countdown">
            <h2>You have until <?= $doors_closing_time->format('g a') ?> to check in</h2>

            <div class="body">
                <div class="remaining_time time_until"
                     data-time="<?= $doors_closing_time->getTimestamp() ?>"></div>
            </div>
        </div>
    </div>

    <?php elseif ($doors_open && $checked_in): ?>
    <div class="body">
        <div class="doors_message large">
            <p>You have already checked into
                <em><?= $college->format_relative_night($party_day) ?>'s party</em>.
                
                <a href="/party/<?= $party->id ?>"
                   class="show_spotlight"
                   data-target="<?= '#party_summary_' . $party->id ?>"
                   data-delay="1000">
                    see below
                </a>
            </p>

            <p>
                Planning on going out <em><?= $college->format_relative_night($next_party_day) ?></em>?
                Check in at <?= $next_doors_opening_time->format('g a') ?> <em>after</em> the party.
            </p>

            <p>

            <div class="remaining_time time_until" data-time="<?= $next_doors_opening_time->getTimestamp() ?>"></div>
            </p>
        </div>
    </div>

    <?php  elseif (!$doors_open): ?>
    <div class="body">
        <div class="doors_message large">
            <p>
                Planning on going out <em><?= $college->format_relative_night($next_party_day) ?></em>?
                Check in at <?= $next_doors_opening_time->format('g a') ?> <em>after</em> the party.
            </p>

            <p>

            <div class="remaining_time time_until" data-time="<?= $next_doors_opening_time->getTimestamp() ?>"></div>
            </p>
        </div>
    </div>
    <?php endif; ?>

</div>
