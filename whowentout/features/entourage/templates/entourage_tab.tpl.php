<?php
/* @var $entourage_engine EntourageEngine */
$entourage_engine = build('entourage_engine');
$entourage = $entourage_engine->get_entourage_count(auth()->current_user());
$n = count($entourage);
?>

<?php if (auth()->logged_in()): ?>
    <?= a('entourage', "My Entourage ($n)", array('class' => 'entourage_link')); ?>
<?php endif; ?>
