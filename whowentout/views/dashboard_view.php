<?= load_section_view('parties_attended_view', "My Parties", array(
                                                 'description' => '<p class="user_command_notice"></p>',
                                                          )) ?>

<fieldset class="upcoming_parties_section">
    <legend>
        <h1>Upcoming Parties</h1>
    </legend>
    <?= load_view('upcoming_parties_view') ?>
</fieldset>
