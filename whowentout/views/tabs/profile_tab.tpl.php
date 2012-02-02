<?php if (auth()->logged_in()): ?>
    <?= a('profile/' . auth()->current_user()->id, 'My Profile', array('class' => 'profile_link')) ?>
<?php endif; ?>