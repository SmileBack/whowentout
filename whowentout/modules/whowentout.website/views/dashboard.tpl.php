<?= r('section', array(
                   'title' => 'My Parties',
                   'body' => '',
                 )) ?>

<?= r('featured_message') ?>

<?=
r('parties_attended', array(
                           'user' => current_user(),
                           'smile_engine' => $smile_engine,
                           'party_groups' => $party_groups,
                      ))
?>

