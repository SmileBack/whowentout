<?php

class CI_Option
{

    private $ci;
    private $cache = array();

    function __construct()
    {
        $this->ci =& get_instance();
        $this->db = $this->ci->db;
    }

    function set($name, $value)
    {
        $value = serialize($value);

        if ($this->exists($name)) {
            $this->db->where('id', $name);
            $this->db->update('options', array('id' => $name, 'value' => $value));
        }
        else {
            $this->db->insert('options', array('id' => $name, 'value' => $value));
        }

        $this->cache[$name] = unserialize($value);
    }

    function get($name, $default = NULL)
    {
        if (!isset($this->cache[$name])) {
            $options = $this->db->select('value')
                    ->from('options')
                    ->where('id', $name)
                    ->get()->result();
            $option = empty($options) ? NULL : $options[0];
            
            if ($option == NULL) {
                $this->set($name, $default);
            }
            else {
                $this->cache[$name] = unserialize($option->value);
            }
        }

        return $this->cache[$name];
    }

    function exists($name)
    {
        if (isset($this->cache[$name]))
            return TRUE;
        
        return $this->db->from('options')
                        ->where('id', $name)
                        ->count_all_results() > 0;
    }

    function delete($name)
    {
        $this->db->delete('options', array('id' => $name));
        unset($this->cache[$name]);
    }

}
