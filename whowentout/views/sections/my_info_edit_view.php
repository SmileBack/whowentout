
<?= form_open_multipart('user/edit_save', array('id' => 'edit_form')) ?>
  
  <fieldset>
    <ul>
      <li>
        <label>Name</label>
        <input type="text" value="<?= $user->full_name ?>" disabled="disabled" />
      </li>
      <li>
        <label>School</label>
        <input type="text" value="<?= $user->college->name ?>" disabled="disabled" />
      </li>
      <li class="<?= in_array('grad_year', $missing_info) ? 'missing' : '' ?>">
        <label>Graduation Year</label>
        <?= grad_year_dropdown($user->grad_year) ?>
      </li>
      <li class="<?= in_array('hometown', $missing_info) ? 'missing' : '' ?>">
        <label>Hometown</label>
        <input type="text" name="hometown" value="<?= $user->hometown ?>" />
      </li>
    </ul>
  </fieldset>
  
  <fieldset>
    <div id="crop" class="frame">
      <?= $user->raw_pic ?>
    </div>

    <div id="crop_preview_frame" class="frame">
      <div id="crop_preview"> 
        <?= $user->raw_pic ?>
      </div>
    </div>
    
    <div id="pic_options">
      <?php if ( $user->has_pic('upload') ): ?>
        <label>Use your profile picture from Facebook</label>
        <input type="submit" name="op" value="Use Facebook Pic" />
      <?php else: ?>
        <label>Upload your own pic</label>
        <?= form_upload('upload_pic') ?>
        <input type="submit" name="op" value="Upload" />
      <?php endif; ?>
    </div>
    
    <input type="hidden" id="x" name="x" value="<?= $user->pic_x ?>"/>
    <input type="hidden" id="y" name="y" value="<?= $user->pic_y ?>"/>
    <input type="hidden" id="width" name="width" value="<?= $user->pic_width ?>" />
    <input type="hidden" id="height" name="height" value="<?= $user->pic_height ?>" />

  </fieldset>


  <fieldset class="form_buttons">
    <input type="submit" name="op" value="Save" />
  </fieldset>
  
<?= form_close() ?>
