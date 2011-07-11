<?= form_open('admin/fake_time'); ?>
  
  <fieldset>
    
    <label>Fake Time</label>
    <select name="fake_time">
      <option value="2011-10-07 15:01:00 -0700">3:01 pm Fri, Oct 7, 2011</option>
      <option value="2011-10-07 22:55:00 -0700">10:56 pm Fri, Oct 7, 2011</option>
      <option value="2011-10-14 22:05:00 -0700">10:05 pm Fri, Oct 14, 2011</option>
    </select>
  </fieldset>
  
  <fieldset>
    <label>Real Time</label>
    <p><?= $real_time ?></p>
  </fieldset>

  <fieldset>
    <label>Delta</label>
    <p><?= $delta ?></p>
  </fieldset>

<input type="submit" value="Save" />

<?= form_close(); ?>
