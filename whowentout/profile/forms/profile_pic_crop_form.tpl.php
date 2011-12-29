<?php
/* @var $profile_picture ProfilePicture */
$box = $profile_picture->get_crop_box();
?>
<form method="post" action="/profile/crop" class="profile_pic_crop_form">

    <div class="coordinates">
        <input type="text" name="x" value="<?= $box->x ?>"/>
        <input type="text" name="y" value="<?= $box->y ?>"/>
        <input type="text" name="width" value="<?= $box->width ?>"/>
        <input type="text" name="height" value="<?= $box->height ?>"/>
    </div>

    <input type="submit" value="Update"/>
</form>
