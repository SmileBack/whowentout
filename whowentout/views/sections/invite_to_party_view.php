<?= form_open('party/invite') ?>

  <p>Did you see someone last night who isn't here? We can send them an email reminding them to check in!</p>
  <p>(In case you're wondering, your identity will not show up in the email)</p>
  
  <label>Type their name here:</label>
  <input class="name autocomplete" type="text" name="name" source="/college/students" extra_class="name" />
  <input class="submit_button" type="Submit" />
  
<?= form_close() ?>
