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

        <?php if ($your_profile): ?>
        <section class="entourage_requests_section">
            <h3>Entourage Requests</h3>
            <?= r::entourage_requests(); ?>
        </section>
        <?php endif; ?>

        <section class="entourage_section">
            <h3>Entourage</h3>
            <?= r::profile_gallery(array('users' => $entourage, 'preset' => 'thumb', 'link_to_profile' => true)) ?>
        </section>

        <section class="checkins_section">
            <h3>Checkins</h3>
            <?= r::profile_checkins(array('user' => $user)); ?>
        </section>

        <?php if (!$your_profile): ?>
        <section class="mutual_friends_section">
            <h3>Mutual Friends</h3>
            <?= r::profile_gallery(array('users' => $mutual_friends, 'preset' => 'facebook.normal', 'show_networks' => true)); ?>
        </section>
        <?php endif; ?>

    </div>
</div>
