<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';
boot();

/* @var $package_installer PackageInstaller */
$package_installer = build('package_installer');
$package_installer->update('WhoWentOutPackage');
print "Updated package";
?>