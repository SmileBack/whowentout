<?= r('section', array(
                   'title' => 'My Parties',
                   'body' => '',
                 )) ?>

<?=
r('parties_attended', array(
                           'user' => current_user(),
                           'smile_engine' => $smile_engine,
                      ))
?>

