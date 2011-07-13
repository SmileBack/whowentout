
<?= form_open('user/edit_save', array('id' => 'edit_form')) ?>
  
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
      <li>
        <label>Graduation Year</label>
        <?= grad_year_dropdown($user->grad_year) ?>
      </li>
      <li>
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

    <input type="hidden" id="x" name="x" value="<?= $user->pic_x ?>"/>
    <input type="hidden" id="y" name="y" value="<?= $user->pic_y ?>"/>
    <input type="hidden" id="width" name="width" value="<?= $user->pic_width ?>" />
    <input type="hidden" id="height" name="height" value="<?= $user->pic_height ?>" />

  </fieldset>


  <fieldset class="form_buttons">
    <input type="submit" value="Save" />
  </fieldset>
  
<?= form_close() ?>
