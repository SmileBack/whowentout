<?php

class WhoWentOutUninstaller
{

    function __construct()
    {
    }

    function uninstall($password)
    {
        if ($password == 'dandorroyven') {
            $this->clear_database();
        }
    }

    protected function clear_database()
    {
        $ci =& get_instance();
        $tables = $this->database_tables();
        $ci->db->query('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($tables as $table) {
            $ci->db->truncate($table);
        }
        $ci->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    protected function database_tables()
    {
        $ci =& get_instance();
        $tables = array();
        $rows = $ci->db->query('SHOW TABLES')->result();
        foreach ($rows as $row) {
            $row = (array)$row;
            $tables[] = array_pop($row);
        }
        return $tables;
    }

}
