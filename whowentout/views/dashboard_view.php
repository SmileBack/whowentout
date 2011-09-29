<?php
$message = load_view('user_command_notice', array(
                                              'user' => current_user(),
                                            ));
?>

<?= load_section_view('parties_attended_view', "My Parties", array(
                                                 'description' => $message,
                                                          )) ?>

<fieldset class="upcoming_parties_section">
    <legend>
        <h1>Upcoming Parties on WhoWentOut</h1>
    </legend>
    <?= load_view('upcoming_parties_view') ?>
</fieldset>
