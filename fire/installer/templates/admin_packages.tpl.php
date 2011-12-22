<?php
/**
 * @var $packages string[]
 * @var $installer PackageInstaller
 */
?>

<table>
    <thead>
    <tr>
        <th>Package</th>
        <th>Installed</th>
        <th>Available</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($packages as $package_name): ?>
    <tr>
        <td><?= $package_name ?></td>
        <td><?= $installer->get_installed_version($package_name) ?></td>
        <td><?= $installer->get_available_version($package_name) ?></td>
        <td><?=  a("admin_packages/info/$package_name", $installer->is_installed($package_name) ? 'upgrade' : 'install') ?></td>
    </tr>
        <?php endforeach; ?>
    </tbody>
</table>
