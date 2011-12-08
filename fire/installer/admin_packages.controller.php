<?php

class Admin_Packages_Controller extends Controller
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
        print r::admin_packages(array('packages' => $packages));
    }

    function info($package_name)
    {
        $this->installer->update($package_name);
        redirect('admin_packages');
    }
    
}
