<?php
$title = browser::is_desktop() ? 'My Profile' : 'Profile';
?>
<?php if (auth()->logged_in()): ?>
    <?= a('profile/' . auth()->current_user()->id, $title, array('class' => 'profile_link')) ?>
<?php endif; ?>