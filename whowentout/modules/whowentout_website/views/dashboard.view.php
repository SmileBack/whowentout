<?php
$message = r('user_command_notice', array(
                                         'user' => current_user(),
                                    ))
?>

<?=
r('section', array(
               'title' => "My Parties" . '<span class="num_checkins"></span>',
               'description' => $message,
               'class' => 'parties_attended_view',
               'body' => r('parties_attended', array(
                                                 'user' => current_user(),
                                                 'smile_engine' => $smile_engine,
                                               ))
             ))
?>

<fieldset class="upcoming_parties_section">
    <legend>
        <h1>Upcoming Parties on WhoWentOut</h1>
    </legend>
    <?= r('upcoming_parties', array(
                                'user' => current_user(),
                              )) ?>
</fieldset>
