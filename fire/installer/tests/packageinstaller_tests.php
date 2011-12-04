<?php

define('TEST_PACKAGE_ONE', 'TestPackageOne');
define('TEST_PACKAGE_TWO', 'TestPackageTwo');

class PackageInstaller_Tests extends TestGroup
{


    /**
     * @var Database
     */
    private $db;

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
        $this->db = $factory->build('database');
        $this->drop_all_tables();
        
        $this->installer = $factory->build('package_installer');
    }

    function drop_all_tables()
    {
        foreach ($this->db->list_table_names() as $table_name)
            $this->db->destroy_table($table_name);
    }

    function teardown()
    {
        
    }

    function test_list_packages()
    {
        $packages = $this->installer->list_packages();
        $this->assert_true(in_array(TEST_PACKAGE_ONE, $packages));
        $this->assert_true(in_array(TEST_PACKAGE_TWO, $packages));
    }

    function test_basic_install()
    {
        $this->assert_true(!$this->installer->is_installed(TEST_PACKAGE_ONE), 'package isnt installed beforehand');

        $this->installer->install(TEST_PACKAGE_ONE);
        $this->assert_true($this->installer->is_installed(TEST_PACKAGE_ONE), 'package IS installed afterword');
        $this->assert_true($this->db->has_table('table_one'), 'table has been successfully created');
        $this->assert_equal($this->installer->get_installed_version(TEST_PACKAGE_ONE), '1.5.2');
        $this->assert_true(!$this->installer->is_installed(TEST_PACKAGE_TWO), 'other package still isnt installed');

        $this->installer->install(TEST_PACKAGE_TWO);
        $this->assert_true($this->installer->is_installed(TEST_PACKAGE_TWO), 'other package is now installed');

        $this->installer->uninstall(TEST_PACKAGE_ONE);
        $this->assert_true(!$this->installer->is_installed(TEST_PACKAGE_ONE), ' first package got uninstalled');
        $this->assert_true(!$this->db->has_table('table_one'), 'table has been successfully destroyed');
        $this->assert_true($this->installer->is_installed(TEST_PACKAGE_TWO), 'second package is still installed');

        $this->installer->uninstall(TEST_PACKAGE_TWO);
        $this->assert_true(!$this->installer->is_installed(TEST_PACKAGE_TWO), 'other package got uninstalled');
    }

    function test_available_version()
    {
        $package_one_version = $this->installer->get_available_version(TEST_PACKAGE_ONE);
        $package_two_version = $this->installer->get_available_version(TEST_PACKAGE_TWO);
        
        $this->assert_equal($package_one_version, '1.5.2');
        $this->assert_equal($package_two_version, '1.2.1');
    }
    
}
