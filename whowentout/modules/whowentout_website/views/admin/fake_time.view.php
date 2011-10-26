<section>
  <h1>Fake Time</h1>
  <div class="section_content">
    <?= form_open('admin/fake_time'); ?>

      <fieldset>

        <label>Fake Time</label>
          <input name="fake_time" value="" autocomplete="off" />

        <select class="fake_time_options">
            <option value=""></option>
            <option value="Friday 3:00pm">Friday 3:00pm</option>
            <option value="Friday 10:00pm">Friday 10:00pm</option>
            <option value="Friday 11:59:30pm">Friday 11:59:30pm</option>
            <option value="Saturday 1:59:30am">Saturday 1:59:30am</option>
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
    