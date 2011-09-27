<?= load_section_view('parties_attended_view', "My Parties", array(
                                                 'description' => '<p class="user_command_notice"></p>',
                                                          )) ?>

<h1>Upcoming Parties</h1>
<?= load_view('upcoming_parties_view') ?>
