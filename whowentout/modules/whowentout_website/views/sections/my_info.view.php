<?php if (!isset($user)) $user = current_user(); ?>

<div class="profile_pic">
  <?= $user->pic ?>
  <?= anchor('user/edit', 'change', array('class' => 'edit')); ?>
</div>


<div class="profile_info">
  <p class="name"><?= $user->first_name ?> <?= $user->last_name ?></p>
  <p class="hometown"><?= $user->hometown ?></p>
  <p class="college"><?= $user->college->name ?> <?= $user->grad_year ? $user->grad_year : '' ?></p>
</div>

<?= anchor('user/edit', 'edit', array('class' => 'edit_link')) ?>