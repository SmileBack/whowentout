<?= a_open("profile/$user->id") ?>
<div class="profile_small <?= $class ?>">

    <?php if ($hidden): ?>
        <?= img('/images/profile_anonymous.png'); ?>
    <?php else: ?>

    <div class="gallery_thumb">

        <?php if ($badge): ?>
            <div class="profile_badge <?= $badge ?>"><?= $badge ?></div>
        <?php endif; ?>

        <?php if ($link_to_profile): ?>
           
        <?php endif; ?>

        <?=
            img($profile_picture_url, array(
                'data-user_id' => $user->id,
                'class' => ($defer_load ? 'img_load' : 'img_loaded')
                           . " profile_picture_{$user->id}",
            ))
        ?>

        <?php if ($link_to_profile): ?>
            
        <?php endif; ?>

    </div>

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
	
<?= a_close() ?>
</div>
