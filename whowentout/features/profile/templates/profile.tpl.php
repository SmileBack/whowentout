<div class="profile">
    <div class="profile_inner">

        <div class="profile_main">
            <div class="profile_pic">
                <?= img($profile_picture_url) ?>

                <?php if ($your_profile && browser::is_desktop()): ?>
                <a title="Edit Profile" href="/profile/picture/edit" class="action profile_edit_picture_link">Change</a>
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

        <?= r::entourage_section(array('user' => $user, 'show_invite_link' => $your_profile)); ?>

        <section class="checkins_section">
            <h3>
                <?php if ($user == auth()->current_user()): ?>
                    <span>Your Checkins</span>
                <?php else: ?>
                    <span><?= "$user->first_name" ?>'s Checkins</span>
                <?php endif; ?>
            </h3>
            <?= r::profile_checkins(array('user' => $user)); ?>
        </section>

        <?php if (!$your_profile): ?>
        <section class="mutual_friends_section">
            <h3>Mutual Friends (<?= count($mutual_friends) ?>)</h3>
            <?php if (count($mutual_friends) > 0): ?>
                <?= r::profile_gallery(array('users' => $mutual_friends, 'preset' => 'facebook.normal', 'show_networks' => true)); ?>
            <?php else: ?>
                <h2>You and <?= $user->first_name ?> have no friends in common.</h2>
            <?php endif; ?>
        </section>
        <?php endif; ?>

    </div>
</div>
