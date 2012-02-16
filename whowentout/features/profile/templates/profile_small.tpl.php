<div class="profile_small <?= $class ?>">

    <div class="profile_badge"><?= $is_friend ? 'friend' : '' ?></div>

    <?php if ($hidden): ?>
        <?= img('/images/profile_anonymous.png'); ?>
    <?php else: ?>

        <?php if ($link_to_profile): ?>
            <?= a_open("profile/$user->id") ?>
        <?php endif; ?>

        <div class="gallery_thumb">
            <?= img($profile_picture_url) ?>
        </div>

        <?php if ($link_to_profile): ?>
            <?= a_close() ?>
        <?php endif; ?>

    <?php endif; ?>

    <div class="profile_name">
        <?php if ($hidden): ?>
            &nbsp;
        <?php else: ?>
            <?= $user->first_name . ' ' . $user->last_name ?>
        <?php endif; ?>
    </div>

    <?php benchmark::start('profile_networks'); ?>
    <?php if (!$hidden && $show_networks): ?>
        <?= r::profile_networks(array('user' => $user)) ?>
    <?php endif; ?>
    <?php benchmark::end('profile_networks'); ?>

</div>
