<?php

class TestPackageOne extends Package
{

    public $version = '0.5';

    function install()
    {
        $this->db()->create_table('table_one', array(
                                         'id' => array('type' => 'id'),
                                       ));
    }

    function uninstall()
    {
        $this->db()->destroy_table('table_one');
    }

    /**
     * @return Database
     */
    private function db()
    {
        return factory('package_installer_test')->build('database');
    }
    
}
