<div class="profile_edit_picture">

    <div class="profile_pic">
        <?= img($profile_picture->url('source'), array('class' => 'profile_pic_source')) ?>
    </div>

    <div class="profile_pic_options">
        <ul>
            <li><?= r::profile_pic_upload_form() ?></li>
            <li><?= r::profile_pic_facebook_form() ?></li>
        </ul>
    </div>

    <?= r::profile_pic_crop_form(array(
        'profile_picture' => $profile_picture,
    )) ?>

</div>
