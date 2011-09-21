<?= form_open('party/invite') ?>

  <a id="invite_to_party"></a>

  <label>Type their name here:</label>
  <input class="name autocomplete" type="text" name="name" source="/college/students" extra_class="name" />
  <input class="submit_button" type="Submit" />
  
<?= form_close() ?>