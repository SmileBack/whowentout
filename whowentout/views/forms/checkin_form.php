<?php
$checkin_state = new UserCheckinState( current_user() );
$doors_open = $checkin_state->door_is_open();
$doors_opening_time = $checkin_state->get_door_opening_time();
$doors_closing_time = $checkin_state->get_door_closing_time();
$user_has_checked_in = $checkin_state->user_has_checked_in();
$next_party_day = $checkin_state->get_next_party_day();
$party_day = $checkin_state->get_party_day();
?>

<div class="party_summary checkin" data-party-date="<?= $party_day->format('Y-m-d') ?>">


    <?php if ( $doors_open && ! $user_has_checked_in ): ?>
    <h2>
        <?= form_open('checkin', array(
                                      'class' => 'checkin_form',
                                      'doors_opening_time' => $doors_opening_time->getTimestamp(),
                                      'doors_closing_time' => $doors_closing_time->getTimestamp(),
                                      'doors_open' => (int)$doors_open,
                                 )) ?>
        <?= $college->format_time($party_day) ?>
        |
        <?= parties_dropdown( $checkin_state->get_open_parties() ) ?>
        <button type="submit" class="submit_button">check in</button>
        <?= form_close() ?>
    </h2>

    <div class="badge">open for checkin</div>

    <div class="body">
        <?= load_view('recent_attendees_view', array('count' => 4, 'party' => NULL)) ?>
        <div class="countdown">
            <h2>You have until <?= $doors_closing_time->format('g a') ?> to check in</h2>

            <div class="remaining_time time_until"
                 data-time="<?= $doors_closing_time->getTimestamp() ?>"></div>
        </div>
    </div>
    <?php elseif ($doors_open && $user_has_checked_in): ?>

    <div class="body">

        <div class="doors_message">

            <p class="primary">
                You have checked in to last night's party.
            </p>

            <p class="secondary">
                Check-ins will reopen after

                   <a href=".day_summary[data-day=<?= $next_party_day->format('Y-m-d') ?>]"
                   class="party_summary_link scroll"
                   data-flash-spotlight="1">

                       <?= $next_party_day->format('l') ?>'s parties.

                   </a>

            </p>

        </div>

    </div>

    <?php  elseif (!$doors_open): ?>

    <div class="body">

        <div class="doors_message">

            <div class="remaining_time time_until"
                 data-time="<?= $doors_opening_time->getTimestamp() ?>"></div>
            
            <p class="secondary">
                Check-ins will reopen after

                   <a href=".day_summary[data-day=<?= $next_party_day->format('Y-m-d') ?>]"
                   class="party_summary_link scroll"
                   data-flash-spotlight="1">

                       <?= $next_party_day->format('l') ?>'s parties.

                   </a>

            </p>

        </div>

    </div>
    <?php endif; ?>

</div>

