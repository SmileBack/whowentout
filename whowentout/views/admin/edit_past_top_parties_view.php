<section>
  <h1>Edit Past Top Parties</h1>
  <div class="section_content">
    <?= form_open('admin/save_past_top_parties') ?>
    <label>Past top parties html</label>
    <textarea name="past_top_parties_html" style="width: 90%; height: 500px;">
    <?= $html ?>
    </textarea>
    <input type="submit" value="Save" />
    <?= form_close() ?>
  </div>
</section>