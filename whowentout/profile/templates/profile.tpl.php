<?php

?>

<div class="profile">

    <div class="profile_main">
        <div class="profile_pic">
            <?= img($profile_picture_url) ?>
            <a href="/profile/edit" class="edit_profile_link">Change</a>
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

        <ul class="entourage">
            <?php foreach ($entourage as $user): ?>
                <li><?= r::profile_small(array('user' => $user)) ?></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="mutual_friends_section">
        <h3>Mutual Friends</h3>

        <ul class="profile_mutual_friends">
           <?php foreach ($mutual_friends as $friend): ?>
                <li><?= r::profile_small(array('user' => $friend, 'show_networks' => true)) ?></li>
           <?php endforeach; ?>
        </ul>
    </section>

</div>
