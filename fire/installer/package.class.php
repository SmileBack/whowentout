<?php

abstract class Package
{

    public $version = '0.1';

    function install()
    {
    }

    function uninstall()
    {
    }

    function get_versions()
    {
        $versions = array();

        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if ($this->is_upgrade_method($method)) {
                $versions[] = $this->get_upgrade_method_version($method);
            }
        }
        usort($versions, 'version_compare');
        return $versions;
    }

    private function is_upgrade_method($method)
    {
        return preg_match('/^upgrade_to_.+/', $method) == 1;
    }

    private function get_upgrade_method_version($method)
    {
        $method = preg_replace('/^upgrade_to_/', '', $method);
        $method = preg_replace('/\D+/', '.', $method);
        return $method;
    }

}
