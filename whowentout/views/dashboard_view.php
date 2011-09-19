<?=
load_section_view('last_night_view', 'Where Did You Go Out Last Night?', array(
                                                                              'description' => 'This section allows you to check in to the party you attended the night before.',
                                                                         ))
; ?>

<?=
load_section_view('my_info_view', 'My Info', array(
                                                  'description' => 'This section displays your info.'
                                             ))
; ?>

<?= load_section_view('parties_attended_view', "Parties I've Attended This Week", array(
                                                 'description' => 'This section displays all the parties you have checked in to.',
                                                                                  ))
; ?>

<?= load_section_view('parties_this_week_view', "This Week's Batch of Parties", array(
                                                  'description' => 'This section displays the parties that will appear on WhoWentOut this week. The next batch of parties will be displayed on Wednesday at 11:59pm.',
                                                                                ))
; ?>
