<?php
$yesterday = college()->yesterday(TRUE);
$open_parties = college()->open_parties(current_time());
?>

<?=
form_open('checkin', array(
                          'id' => 'checkin_form',
                          'doors_opening_time' => college()->get_opening_time()->getTimestamp(),
                          'doors_closing_time' => college()->get_closing_time()->getTimestamp(),
                          'doors_open' => (int)college()->doors_are_open(),
                     ))
?>

<?php if ( college()->within_checkin_periods() ): ?>
   <?php $checkins_begin_time = college()->checkins_begin_time(TRUE); ?>
   <?php $first_party_night = clone $checkins_begin_time; $first_party_night->modify('-1 day') ?>
   Doors will open for <?= $first_party_night->format('l') ?> night checkins on
   <?= $checkins_begin_time->format('l \a\t g a') ?>

<?php elseif (college()->doors_are_open()): ?>

    <?php if (logged_in() && current_user()->has_attended_party_on_date($yesterday)): ?>
        You attended <?= anchor("party/$party->id", $party->place->name) ?>. Here are the most recent checkins.
        <?= load_view('recent_attendees_view', array('party' => $party)) ?>
    <?php else: ?>
        <?= parties_dropdown($open_parties) ?>
        <button type="submit">enter</button>
    <?php endif; ?>

    <span class="closing_time doors_open" time="<?= college()->get_closing_time()->getTimestamp() ?>">

        Doors will close for checkin at
        <?= college()->get_closing_time(TRUE)->format('g a') ?>
        [ in <span class="remaining_time"></span> ]
    </span>

<?php elseif (college()->doors_are_closed()): ?>

    <?= parties_dropdown($open_parties) ?>
    <button type="submit">enter</button>

    <span class="closing_time doors_closed"
          time="<?= college()->get_closing_time()->getTimestamp() ?>">

            Doors will open for checkin at
        <?= college()->get_opening_time(TRUE)->format('g a') ?>

    </span>

    <?php endif; ?>

<?= form_close() ?>