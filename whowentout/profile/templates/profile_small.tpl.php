<?php
/* @var $profile_picture ProfilePicture */
$profile_picture = factory()->build('profile_picture', $user);
$hidden = isset($hidden) ? $hidden : false;
?>

<div class="profile_small">
    <div class="profile_caption">
        <?= $caption ?>
    </div>

    <?php if ($hidden): ?>
        <?= img('/images/profile_anonymous.png'); ?>
    <?php else: ?>
        <?= img($profile_picture->url('thumb')) ?>
    <?php endif; ?>

    <div class="profile_name">
        <?php if ($hidden): ?>
            &nbsp;
        <?php else: ?>
            <?= $user->first_name . ' ' . $user->last_name ?>
        <?php endif; ?>
    </div>

    <?php if ($hidden): ?>
        &nbsp;
    <?php else: ?>
        <?= r::profile_networks(array('user' => $user)) ?>
    <?php endif; ?>
</div>
