<ul class="login_gallery">
  <?php foreach ($students as $student): ?>
    <li>
      <a href="user/fakelogin/<?= $student->id ?>">
        <?= $student->thumb ?>
      </a>
      <p>
        <?= $student->full_name ?>
      </p>
    </li>
  <?php endforeach; ?>
</ul>