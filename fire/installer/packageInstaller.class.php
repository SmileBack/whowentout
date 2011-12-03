<?php

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
        
        $this->_create_table_if_needed();
    }
    
    function install($package)
    {
        
    }

    function uninstall($package)
    {
        
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

    function get_installed_version($package)
    {

    }

    function get_avaliable_version($package)
    {
        
    }

    function _create_table_if_needed()
    {
        if ( ! $this->db->table_exists('fire_packages')) {
            $this->db->create_table('fire_packages', array(
                                                       'name' => array('type' => 'key'),
                                                       'version' => array('type' => 'integer', 'null' => false, 'default' => 0),
                                                       'status' => array('type' => 'string'),
                                                     ));
        }
    }

}
