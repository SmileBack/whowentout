<?php
/* @var $profile_picture ProfilePicture */
$profile_picture = factory()->build('profile_picture', $user);
?>

<div class="profile_small">
    <?= img($profile_picture->url('thumb')) ?>
    <div class="profile_name">
    <?= $user->first_name . '  ' . substr($user->last_name, 0, 1) . '.' ?>
    </div>
</div>
    