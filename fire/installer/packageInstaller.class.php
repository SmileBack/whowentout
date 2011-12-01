<?php

class PackageInstaller
{

    /**
     * @var Database
     */
    protected $db;

    function __construct(Database $db)
    {
        $this->db = $db;
        $this->_create_table_if_needed();
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
