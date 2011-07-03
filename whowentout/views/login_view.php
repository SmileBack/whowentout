<?= form_open('login'); ?>
  <label>User ID</label>
  <input name="user_id" value="" />
  <input type="submit" value="Fake Login" />
  
  <?= anchor_facebook_login(); ?>
<?= form_close(); ?>
