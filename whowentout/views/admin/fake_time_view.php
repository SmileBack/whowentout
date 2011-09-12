<section>
  <h1>Fake Time</h1>
  <div class="section_content">
    <?= form_open('admin/fake_time'); ?>

      <fieldset>

        <label>Fake Time</label>
        <select name="fake_time">
          <option value="2011-10-06 15:01:00">Oct 6</option>
          <option value="2011-09-10 19:00:00">7:00 pm Sat, Sep 10, 2011</option>
          <option value="2011-10-07 01:10:00">1:10 am Fri, Oct 7, 2011</option>
          <option value="2011-10-07 01:59:40">1:59:40 am Fri, Oct 7, 2011</option>
          <option value="2011-10-07 15:01:00">*3:01 pm Fri, Oct 7, 2011</option>
          <option value="2011-10-07 22:55:00">*10:53 pm Fri, Oct 7, 2011</option>
          <option value="2011-10-07 22:58:00">10:58 pm Fri, Oct 7, 2011</option>
          <option value="2011-10-07 22:59:00">10:59 pm Fri, Oct 7, 2011</option>
          <option value="2011-10-07 22:59:40">10:59:40 pm Fri, Oct 7, 2011</option>
          <option value="2011-10-07 23:59:40">11:59:40 pm Fri, Oct 7, 2011</option>
          <option value="2011-10-08 00:59:40">12:59:40 am Sat, Oct 8, 2011</option>
          <option value="2011-10-08 01:05:40">1:05:00 am Sat, Oct 8, 2011</option>
          <option value="2011-10-08 01:59:40">1:59:40 am Sat, Oct 8, 2011</option>
          <option value="2011-10-09 15:01:00">3:01 pm Sun Oct 9, 2011</option>
          <option value="2011-10-09 23:59:40">11:59:40 pm Sun Oct 9, 2011</option>
          <option value="2011-10-14 22:05:00">*10:05 pm Fri, Oct 14, 2011</option>
          <option value="2011-10-17 22:05:00">*10:05 pm Fri, Oct 17, 2011</option>
          <option value="2011-11-20 15:01:00">*3:01 pm Fri, Nov 20, 2011</option>
        </select>
      </fieldset>
      
      <fieldset>
        <label>Delta</label>
        <p><?= $delta ?></p>
      </fieldset>

    <input type="submit" value="Save" />

    <?= form_close(); ?>
  </div>
</section>