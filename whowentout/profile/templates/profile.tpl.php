<?php
$dob = $user->date_of_birth;
$now = app()->clock()->get_time();
$age = $dob ? $dob->diff($now)->y : null;

/* @var $profile_picture ProfilePicture */
$profile_picture = factory()->build('profile_picture', $user);
?>

<div class="profile">
    <div class="profile_pic">
        <?= img($profile_picture->url('thumb')) ?>
        <a href="/profile/edit" class="edit_profile_link">Change</a>
    </div>

    <div class="profile_info">
        <h3><?= $user->first_name . ' ' . $user->last_name ?></h3>
        <div>Colleges</div>
        <ul class="profile_networks">
            <?php foreach ($user->networks->where('type', 'college') as $network): ?>
            <li><?= $network->name ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

</div>
