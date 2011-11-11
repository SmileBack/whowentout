<?php
/* @var $student XUser */
?>
<ul class="login_gallery">
  <?php foreach ($students as $student): ?>
    <li>
      <a href="/admin/fakelogin/<?= $student->id ?>">
        <?php $profile_picture = new UserProfilePicture($student); ?>
        <?= $profile_picture->img('thumb') ?>
      </a>
      <p>
        <?= $student->full_name ?>
      </p>
    </li>
  <?php endforeach; ?>
</ul>