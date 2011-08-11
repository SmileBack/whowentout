
<div class="profile_pic">
  <?= $user->pic ?>
  <?= anchor('user/edit', 'edit', array('class' => 'edit')); ?>
</div>


<div class="profile_info">
  <p><?= $user->first_name ?> <?= $user->last_name ?></p>
  <p><?= $user->hometown ?></p>
  <p><?= $user->college->name ?> <?= $user->grad_year ? $user->grad_year : '' ?></p>
</div>
