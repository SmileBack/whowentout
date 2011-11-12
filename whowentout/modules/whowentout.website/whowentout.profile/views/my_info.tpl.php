<?php
/* @var $user XUser */
$profile_picture = new UserProfilePicture($user);
?>

<div class="profile_pic">
    <?= $profile_picture->img('thumb', TRUE) ?>
    <?= anchor('user/edit', 'change', array('class' => 'edit')); ?>
</div>


<div class="profile_info">
    <p class="name">
        <?= $user->first_name ?> <?= $user->last_name ?>
        <span class="online_badge"></span>
    </p>

    <p class="hometown"><?= $user->hometown ?></p>

    <?php if ($user->college && $user->grad_year): ?>
    <p class="college">
        <?= $user->college->name ?> <?= $user->grad_year ? $user->grad_year : '' ?>
    </p>
    <?php endif; ?>

    <?= anchor('user/edit', 'edit', array('class' => 'edit_link')) ?>

</div>
