

<div class="profile_pic">
  <?= $user->pic ?>
  <p class="user_edit"><?= anchor('user/edit', 'edit'); ?></p>
</div>


<div id="user_caption">
  <p><?= $user->first_name ?> <?= $user->last_name ?></p>
  <p><?= $user->hometown ?></p>
  <p><?= $user->college->name ?> <?= $user->grad_year ? $user->grad_year : '' ?></p>
</div>

