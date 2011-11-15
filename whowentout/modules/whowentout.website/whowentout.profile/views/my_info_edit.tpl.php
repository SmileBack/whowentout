<?php $profile_picture = new UserProfilePicture($user); ?>

<?= form_open_multipart('user/update', array('id' => 'edit_form')) ?>

<fieldset class="my_info">
    <ul>
        <li class="name">
            <label>1) Name:</label>
            <input type="text" value="<?= $user->full_name ?>" disabled="disabled"/>
        </li>
        <li class="school">
            <label>2) School:</label>
            <?php if ($user->college): ?>
            <input type="text" value="<?= $user->college->name ?>" disabled="disabled"/>
            <?php else: ?>
            <input type="text" value="No College Listed" disabled="disabled"/>
            <?php endif; ?>
        </li>
        <li class="grad_year <?= in_array('grad_year', $missing_info) ? 'missing' : '' ?>">
            <label>3) Graduation Year:</label>
            <?= grad_year_dropdown($user->grad_year) ?>
        </li>
        <li class="hometown">
            <label>4) Hometown:</label>

            <div class="<?= in_array('hometown_city', $missing_info) ? 'missing' : '' ?>">
                <label>City</label>
                <input type="text" name="hometown_city"
                       value="<?= get_hometown_city($user->hometown) ?>"/>
            </div>
            <div class="<?= in_array('hometown_state', $missing_info) ? 'missing' : '' ?>">
                <label>State</label>
                <?= state_dropdown('hometown_state', get_hometown_state($user->hometown)) ?>
            </div>
        </li>
    </ul>
</fieldset>


<?php $image_missing = in_array('image', $missing_info); ?>

<fieldset class="my_pic <?= $image_missing ? 'missing' : '' ?>">
    <label>5) Choose Pic:</label>

    <div id="crop_raw_image" style="display: none;"><?= $profile_picture->img('source', TRUE) ?></div>

    <div id="crop" class="frame">
    </div>

    <div id="crop_preview_frame" class="frame">
        <div id="crop_preview">
        </div>
    </div>

    <div id="pic_options">
        <span class="file_wrapper upload_pic">
            <input type="file" name="upload_pic" id="pic_upload_input"/>
            <input type="submit" name="op" class="submit_button" value="Upload Pic" />
        </span>

        <div class="or">OR</div>

        <div class="use_facebook_pic">
            <input type="submit" name="op" value="Use Facebook Pic" class="submit_button"/>
        </div>
    </div>

    <?php $crop_box = $profile_picture->get_crop_box() ?>
    <input type="hidden" id="x" name="x" value="<?= $crop_box->x ?>"/>
    <input type="hidden" id="y" name="y" value="<?= $crop_box->y ?>"/>
    <input type="hidden" id="width" name="width" value="<?= $crop_box->width ?>"/>
    <input type="hidden" id="height" name="height" value="<?= $crop_box->height ?>"/>

</fieldset>


<fieldset class="form_buttons">
    <input type="submit" name="op" value="Save" class="submit_button"/>
</fieldset>

<?= form_close() ?>
