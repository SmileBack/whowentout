

<div class="profile_pic">
  <?= $user->pic ?>
  <p class="change_pic"><?= anchor('change_pic', 'change pic'); ?></p>
</div>


<div id="user_caption">
  <p><?= $user->first_name ?> <?= $user->last_name ?></p>
  <p>Fresh Meadows, NY</p>
  <p><?= $user->college->name ?> <?= $user->grad_year ?></p>
</div>
