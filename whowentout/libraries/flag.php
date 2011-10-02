<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Flag
{

    function __construct()
    {
        $this->ci =& get_instance();
        $this->db = $this->ci->db;
    }

    function set($key1 = NULL, $key2 = NULL)
    {
        if ( call_user_func_array(array($this, 'missing'), func_get_args()) ) {
            $this->db->insert('flags', array(
                                            'id' => $this->key(func_get_args())
                                       ));
        }
    }
    
    function exists($key1 = NULL, $key2 = NULL)
    {
        $k = $this->key(func_get_args());
        return $this->db->from('flags')
                        ->where('id', $k)
                        ->count_all_results() > 0;
    }

    function missing($key1 = NULL, $key2 = NULL)
    {
        return ! call_user_func_array(array($this, 'exists'), func_get_args());
    }

    function remove($key1 = NULL, $key2 = NULL)
    {
        $k = $this->key(func_get_args());
        $this->db->delete('flags', array('id' => $k));
    }

    private function key($keys)
    {
        return implode('::', $keys);
    }

    function __install()
    {
        $ci =& get_instance();
        $ci->db->query("CREATE TABLE `flags` (
                          `id` varchar(512) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB");
    }

    function __uninstall()
    {
        $ci =& get_instance();
        $ci->db->query("DROP TABLE `flags`");
    }
    
}
