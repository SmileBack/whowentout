<?php

class DatabaseCacheDriver extends CacheDriver
{

    function __construct($config)
    {
        parent::__construct($config);

        $this->ci =& get_instance();
        $this->db = $this->ci->db;
    }

    function get($key)
    {
        $options = $this->db->select('data')
                ->from('cache')
                ->where('id', $key)
                ->get()->result();
        $option = empty($options) ? NULL : $options[0];
        return $option ? unserialize($option->data) : NULL;
    }

    function set($key, $data)
    {
        $data = serialize($data);

        if ($this->exists($key)) {
            $this->db->where('id', $key);
            $this->db->update('cache', array('id' => $key, 'data' => $data));
        }
        else {
            $this->db->insert('cache', array('id' => $key, 'data' => $data));
        }
        
    }

    function exists($name)
    {
        return $this->db->from('cache')
                       ->where('id', $name)
                       ->count_all_results() > 0;
    }

    function delete($name)
    {
        $this->db->delete('cache', array('id' => $name));
    }

    function __install()
    {
        $this->ci->db->query("CREATE TABLE `cache` (
                                  `id` varchar(512) NOT NULL,
                                  `data` text,
                                  PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
    }

    function __uninstall()
    {
        $this->ci->db->query("DROP TABLE `cache`");
    }

}
