<ul>
  <?php foreach ($groups as $group): ?>
    <li><?= anchor("test/group/$group", $group) ?></li>
  <?php endforeach; ?>
</ul>
