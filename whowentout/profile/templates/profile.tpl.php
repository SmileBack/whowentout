<?php
$dob = $user->date_of_birth;
$now = app()->clock()->get_time();
$age = $dob ? $dob->diff($now)->y : null;

/* @var $profile_picture ProfilePicture */
$profile_picture = factory()->build('profile_picture', $user);
?>

<?= img($profile_picture->url('thumb')) ?>
<?= $user->first_name . ' ' . $user->last_name ?>

<div>Colleges</div>
<ul class="profile_networks">
    <?php foreach ($user->networks->where('type', 'college') as $network): ?>
    <li><?= $network->name ?></li>
    <?php endforeach; ?>
</ul>

<?= r::profile_pic_upload_form() ?>

<?= r::profile_pic_facebook_form() ?>

<?= img($profile_picture->url('source'), array('class' => 'profile_pic_source')) ?>

<?= r::profile_pic_crop_form(array(
    'profile_picture' => $profile_picture,
)) ?>