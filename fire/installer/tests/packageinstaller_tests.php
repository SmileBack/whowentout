<?php

class PackageInstaller_Tests extends TestGroup
{

    /**
     * @var PackageInstaller
     */
    private $installer;

    function setup()
    {
        
        $factory = factory('package_installer_test', array(
                                                          'package_installer' => array(
                                                              'type' => 'PackageInstaller',
                                                              'database' => 'database',
                                                              'class_loader' => 'class_loader',
                                                          ),
                                                          'database' => array(
                                                              'type' => 'Database',
                                                              'host' => 'localhost',
                                                              'database' => 'fire_test',
                                                              'username' => 'root',
                                                              'password' => 'root',
                                                          ),
                                                     ));

        $this->installer = $factory->build('package_installer');
    }

    function teardown()
    {

    }

}
