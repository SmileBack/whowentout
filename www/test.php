<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';
boot();

/* @var $installer PackageInstaller */
$installer = build('package_installer');
$installer->install('WhoWentOutPackage');

