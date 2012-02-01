<?php
$current_user = auth()->current_user();
/* @var $entourage_engine EntourageEngine */
$entourage_engine = build('entourage_engine');
$entourage = $entourage_engine->get_entourage_users($current_user);
?>

