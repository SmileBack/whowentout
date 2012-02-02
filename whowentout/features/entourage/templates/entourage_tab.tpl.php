<?php
/* @var $entourage_engine EntourageEngine */
$entourage_engine = build('entourage_engine');
$entourage = $entourage_engine->get_entourage_count(auth()->current_user());
$n = count($entourage);
?>

<?php if (auth()->logged_in()): ?>
    <?= a_open('entourage', array('class' => 'entourage_link')); ?>
        <?= "My Entourage ($n)" ?>
    <?= a_close(); ?>
<?php endif; ?>
