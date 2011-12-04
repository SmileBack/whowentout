<?php

class Packages_Controller extends Controller
{

    /* @var $installer PackageInstaller */
    private $installer;

    function __construct()
    {
        $this->installer = factory()->build('package_installer');
    }

    function index()
    {
        $packages = $this->installer->list_packages();
        print r::packages(array('packages' => $packages));
    }

    function install($package_name)
    {
        $this->installer->install($package_name);
    }

    function uninstall($package_name)
    {
        $this->installer->uninstall($package_name);
    }

}
