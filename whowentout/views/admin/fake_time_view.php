<?= form_open('admin/fake_time'); ?>
  
  <fieldset>
    
    <label>Fake Time</label>
    <select name="fake_time">
      <option value="2011-10-07 15:01:00 -0700">*3:01 pm Fri, Oct 7, 2011</option>
      <option value="2011-10-07 22:55:00 -0700">*10:53 pm Fri, Oct 7, 2011</option>
      <option value="2011-10-07 22:58:00 -0700">10:58 pm Fri, Oct 7, 2011</option>
      <option value="2011-10-07 22:59:00 -0700">10:59 pm Fri, Oct 7, 2011</option>
      <option value="2011-10-07 22:59:40 -0700">10:59:40 pm Fri, Oct 7, 2011</option>
      <option value="2011-10-07 23:59:40 -0700">11:59:40 pm Fri, Oct 7, 2011</option>
      <option value="2011-10-08 00:59:40 -0700">12:59:40 am Fri, Oct 8, 2011</option>
      <option value="2011-10-08 01:05:40 -0700">1:05:00 am Fri, Oct 8, 2011</option>
      <option value="2011-10-14 22:05:00 -0700">*10:05 pm Fri, Oct 14, 2011</option>
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
