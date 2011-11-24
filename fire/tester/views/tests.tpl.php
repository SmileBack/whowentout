<ul>
  <?php foreach ($groups as $group): ?>
    <li><?= a("test/group/$group", $group) ?></li>
  <?php endforeach; ?>
</ul>
