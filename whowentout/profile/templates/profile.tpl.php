<?php

?>

<div class="profile">
    <div class="profile_inner">

        <div class="profile_main">
            <div class="profile_pic">
                <?= img($profile_picture_url) ?>

                <?php if ($your_profile): ?>
                <a href="/profile/edit" class="edit_profile_link">Change</a>
                <?php endif; ?>
            </div>

            <div class="profile_info">
                <h2><?= $user->first_name . ' ' . $user->last_name ?></h2>
                <ul class="profile_networks">
                    <?php foreach ($user->networks->where('type', 'college') as $network): ?>
                    <li><?= $network->name ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <section class="entourage_section">
            <h3>Entourage</h3>
            <?= r::profile_gallery(array('users' => $entourage, 'preset' => 'thumb')) ?>
        </section>

        <?php if (!$your_profile): ?>
        <section class="mutual_friends_section">
            <h3>Mutual Friends</h3>
            <?= r::profile_gallery(array('users' => $mutual_friends, 'preset' => 'facebook.normal', 'show_networks' => true)); ?>
        </section>
        <?php endif; ?>

    </div>
</div>
