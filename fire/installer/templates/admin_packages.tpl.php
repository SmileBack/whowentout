<ul>
<?php foreach ($packages as $package): ?>
    <li><?= a("admin_packages/info/$package", $package) ?></li>
<?php endforeach; ?>
</ul>