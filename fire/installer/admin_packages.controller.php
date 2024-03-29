<?php

class Admin_Packages_Controller extends Controller
{

    /* @var $installer PackageInstaller */
    private $installer;

    function __construct()
    {
        $this->installer = build('package_installer');
    }

    function index()
    {
        $packages = $this->installer->list_packages();
        print r::page(array(
            'content' => r::admin_packages(array(
                'packages' => $packages,
                'installer' => $this->installer,
            )),
        ));
    }

    function info($package_name)
    {
        if ($this->installer->is_installed($package_name))
            $this->installer->update($package_name);
        else
            $this->installer->install($package_name);

        redirect('admin_packages');
    }
    
}
