
<?= form_open_multipart('user/edit_save', array('id' => 'edit_form')) ?>
  
  <fieldset class="my_info">
    <ul>
      <li class="name">
        <label>Name</label>
        <input type="text" value="<?= $user->full_name ?>" disabled="disabled" />
      </li>
      <li class="school">
        <label>School</label>
        <?php if ($user->college): ?>
          <input type="text" value="<?= $user->college->name ?>" disabled="disabled" />
        <?php else: ?>
          <input type="text" value="No College Listed" disabled="disabled" />
        <?php endif; ?>
      </li>
      <li class="grad_year <?= in_array('grad_year', $missing_info) ? 'missing' : '' ?>">
        <label>Graduation Year</label>
        <?= grad_year_dropdown($user->grad_year) ?>
      </li>
      <li class="hometown">
        <label>Hometown</label>
        <div class="<?= in_array('hometown_city', $missing_info) ? 'missing' : '' ?>">
          <label>City</label>
          <input type="text" name="hometown_city"
                 value="<?= get_hometown_city($user->hometown) ?>" />
        </div>
        <div class="<?= in_array('hometown_state', $missing_info) ? 'missing' : '' ?>">
          <label>State</label>
            <?= state_dropdown('hometown_state', get_hometown_state($user->hometown)) ?>
        </div>
      </li>
    </ul>
  </fieldset>
  
  <fieldset class="my_pic">
    
    <div id="crop" class="frame">
      <?= $user->raw_pic ?>
    </div>

    <div id="crop_preview_frame" class="frame">
      <div id="crop_preview"> 
        <?= $user->raw_pic ?>
      </div>
    </div>
    
    <div id="pic_options">
      <div>
        <input type="submit" name="op" value="Use Your Facebook Pic" />
      </div>
      <div>
        <label>Or upload your own pic</label>
        <?= form_upload('upload_pic') ?>
        <input type="submit" name="op" value="Upload" />
      </div>
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
