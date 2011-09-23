<?php
$timer = college()->doors_are_open() ? '<span class="remaining_time"></span>' : '';
?>

<?php if (FALSE): ?>
<?= load_section_view('last_night_view', 'Where Did You Go Out Last Night?') ?>

<?php endif; ?>
<?= load_section_view('my_info_view', 'My Info', array(
                                             )) ?>


<?= load_section_view('parties_attended_view', "Parties I've Attended") ?>

<?= load_section_view('parties_this_week_view', "This Week's Batch of Parties") ?>
