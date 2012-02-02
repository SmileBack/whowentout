<?php
/* @var $entourage_engine EntourageEngine */
$entourage_engine = build('entourage_engine');
$entourage = $entourage_engine->get_entourage_users($user);
?>
<?= r::profile_gallery(array('users' => $entourage, 'preset' => 'thumb', 'link_to_profile' => true)) ?>
