<?php
$yesterday = college()->yesterday(TRUE);
$open_parties = college()->open_parties(current_time());
?>

<div class="party_summary">

    <?=
    form_open('checkin', array(
                              'id' => 'checkin_form',
                              'doors_opening_time' => college()->get_opening_time()->getTimestamp(),
                              'doors_closing_time' => college()->get_closing_time()->getTimestamp(),
                              'doors_open' => (int)college()->doors_are_open(),
                         ))
    ?>

    <?php if ( college()->within_checkin_periods() ): ?>

       <div class="doors_message">
           <?php $checkins_begin_time = college()->checkins_begin_time(TRUE); ?>
           <?php $first_party_night = clone $checkins_begin_time; $first_party_night->modify('-1 day') ?>
           Doors will open for <?= $first_party_night->format('l') ?> night check-ins on
           <?= $checkins_begin_time->format('l \a\t g a') ?>
       </div>

    <?php elseif (college()->doors_are_open()): ?>

        <?php if ((logged_in() && !current_user()->has_attended_party_on_date($yesterday)) || !logged_in()): ?>
            <h2>
                <?= college()->format_time($yesterday) ?>
                |
                <?= parties_dropdown($open_parties) ?>
                <button type="submit" class="submit_button">check in</button>
                <span class="closing_time doors_open" time="<?= college()->get_closing_time()->getTimestamp() ?>">

                    <span class="doors_message">
                        Doors will close for check-in at
                        <?= college()->get_closing_time(TRUE)->format('g a') ?>
                        ( in <span class="remaining_time"></span> )
                    </span>

                </span>
            </h2>
        
            <div class="body">
            <?= load_view('recent_attendees_view', array('count' => 4)) ?>
            </div>

        <?php endif; ?>

    <?php elseif (college()->doors_are_closed()): ?>

        <?= parties_dropdown($open_parties) ?>
        <button type="submit" class="submit_button">check in</button>

        <span class="closing_time doors_closed"
              time="<?= college()->get_closing_time()->getTimestamp() ?>">

            <span class="doors_message">
                Doors will open for check-in at
                <?= college()->get_opening_time(TRUE)->format('g a') ?>
            </span>

        </span>

        <?php endif; ?>

    <?= form_close() ?>

</div>