<?php if (login_message()): ?>
  <div class="login_message">
    <?= login_message(); ?>
  </div>
<?php endif; ?>

<?= form_open('login'); ?>
  <label>User ID</label>
  <input name="user_id" value="" />
  <input type="submit" value="Fake Login" />
  
  <?= anchor_facebook_login(); ?>
<?= form_close(); ?>
