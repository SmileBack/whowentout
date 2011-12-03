<?php

define('PACKAGE_STATUS_INACTIVE', 'inactive');
define('PACKAGE_STATUS_INSTALLED', 'installed');

class PackageInstaller
{

    /**
     * @var Database
     */
    protected $db;

    /**
     * @var ClassLoader
     */
    protected $class_loader;

    function __construct(Database $database, ClassLoader $class_loader)
    {
        $this->db = $database;
        $this->class_loader = $class_loader;

        $this->_create_table_if_missing();
    }

    function list_packages()
    {
        return $this->class_loader->get_subclass_names('Package');
    }

    function install($package_name)
    {
        $package_row = $this->get_package_row($package_name);
        if ($package_row) {
            $package = $this->get_package($package_name);
            $package->install();

            $package_row->version = $package->version;
            $package_row->status = PACKAGE_STATUS_INSTALLED;
            $package_row->save();
        }
    }

    function uninstall($package_name)
    {
        $package_row = $this->get_package_row($package_name);
        if ($package_row) {
            /* @var $package Package */
            $package = $this->class_loader->init_subclass('Package', $package_name);
            $package->uninstall();

            $package_row->status = PACKAGE_STATUS_INACTIVE;
            $package_row->version = $package->version;
            $package_row->save();
        }
    }

    function exists($package)
    {
        $packages = $this->list_packages();
        return in_array($package, $packages);
    }

    function is_installed($package_name)
    {
        if (!$this->exists($package_name))
            return false;

        $package_row = $this->get_package_row($package_name);
        return $package_row->status == PACKAGE_STATUS_INSTALLED;
    }

    function upgrade($package)
    {

    }

    function upgrade_to($package, $version)
    {

    }

    function downgrade_to($package, $version)
    {

    }

    function get_installed_version($package_name)
    {
        $package_row = $this->get_package_row($package_name);
        return $package_row ? $package_row->version : null;
    }

    function get_available_version($package_name)
    {
        $package = $this->get_package($package_name);
        return $package ? $package->version : null;
    }

    /**
     * @param  $package_name
     * @return Package
     */
    function get_package($package_name)
    {
        return $this->class_loader->init_subclass('Package', $package_name);
    }

    /**
     * @param  $package_name
     * @return DatabaseRow|null
     */
    function get_package_row($package_name)
    {
        if (!$this->exists($package_name))
            return null;

        if (!$this->table()->row_exists($package_name)) {
            $this->table()->create_row(array(
                                            'name' => $package_name,
                                            'version' => 0,
                                            'status' => 'inactive',
                                       ));
        }

        return $this->table()->row($package_name);
    }

    /**
     * @return DatabaseTable
     */
    private function table()
    {
        return $this->db->table('fire_packages');
    }

    private function _create_table_if_missing()
    {
        if (!$this->db->table_exists('fire_packages')) {
            $this->db->create_table('fire_packages', array(
                                                          'name' => array('type' => 'key'),
                                                          'version' => array('type' => 'string', 'null' => false, 'default' => ''),
                                                          'status' => array('type' => 'string'),
                                                     ));
        }
    }

}
