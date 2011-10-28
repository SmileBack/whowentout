<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function clear_database_dandorroy_ven()
    {
        $this->clear_database();
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

    function index()
    {
        krumo::dump(current_user()->has_facebook_permission('offline_access'));
    }

    function test_email()
    {
        job_call_async('send_email', current_user(), 'hello ' . rand(1, 100), 'hello here is a random number ' . rand(1, 100));
    }

    private function blah()
    {
        print "<h1>woo</h1>";
    }

    function index2()
    {
        $party = XParty::get(32);

        $ven = XUser::get(array('first_name' => 'Venkat'));
        $dan = XUser::get(array('last_name' => 'Berenholtz'));

        $maggie = user(96);
        $claire = user(82);
        $jenny = user(108);
        $allie = user(184);
    }

}
