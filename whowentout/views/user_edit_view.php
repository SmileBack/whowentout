
<?= form_open('user/edit', array('id' => 'edit_form')) ?>
  
  <div id="crop" class="frame">
    <?= $user->raw_pic ?>
  </div>
  
  <div id="crop_preview_frame" class="frame">
    <div id="crop_preview"> 
      <?= $user->raw_pic ?>
    </div>
  </div>
  
  <br/>
  
  <input type="hidden" id="x" name="x" value="<?= $user->pic_x ?>"/>
  <input type="hidden" id="y" name="y" value="<?= $user->pic_y ?>"/>
  <input type="hidden" id="width" name="width" value="<?= $user->pic_width ?>" />
  <input type="hidden" id="height" name="height" value="<?= $user->pic_height ?>" />
  
  <input type="submit" value="Save" />
  
<?= form_close() ?>
