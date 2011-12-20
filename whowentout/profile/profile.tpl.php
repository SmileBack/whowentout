<?php
$dob = $user->date_of_birth;
$now = app()->clock()->get_time();
$age = $dob->diff($now)->y;

/* @var $profile_pic ProfilePicture */
$profile_pic = factory()->build('profile_picture', $user);

?>

<form method="post" accept="/profile/update">
    <fieldset>
        <legend>Basic Info</legend>
        <div>Name: <?= $user->first_name . ' ' . $user->last_name ?></div>
        <div>Age: <?= $age ?></div>
        <div>
            <div>Colleges</div>
            <ul class="profile_networks">
                <?php foreach ($user->networks->where('type', 'college') as $network): ?>
                <li><?= $network->name ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </fieldset>
    <fieldset>
        <legend>Profile Pic</legend>
        <?= img($profile_pic->url('source')) ?>
    </fieldset>

</form>
