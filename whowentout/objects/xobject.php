<?php

class XObject
{

    protected static $objects = array();
    protected static $table = NULL;

    static function get($id)
    {
        $class = get_called_class();

        if ($id == NULL) return NULL;
        if ($id instanceof XObject) return $id;

        if (is_array($id)) {
            $id = self::_get_id($id);
        }

        if ($id == NULL)
            return NULL;

        if (!isset(self::$objects[$class])) {
            self::$objects[$class] = array();
        }

        if (isset(self::$objects[$class][$id])) {
            return self::$objects[$class][$id];
        }
        else {
            $object = new $class($id);
            if ($object->exists()) {
                self::set($id, $object);
                return self::$objects[$class][$id];
            }
            else {
                return FALSE;
            }
        }
    }

    protected static $rows = array();

    public static function pre_fetch_rows($table, $row_ids = array())
    {
        $ci =& get_instance();

        if (empty($row_ids))
            return;

        $rows = $ci->db->from($table)
                       ->where_in('id', $row_ids)
                       ->get()->result();

        foreach ($rows as $row) {
            static::$rows[$table][$row->id] = (array)$row;
        }
    }

    protected static function fetch_row($table, $row_id)
    {
        if (!isset(static::$rows[$table]))
            static::$rows[$table] = array();

        if ( ! isset(static::$rows[$table][$row_id]) ) {
            $ci =& get_instance();
            static::$rows[$table][$row_id] = (array)$ci->db->from($table)
                    ->where('id', $row_id)
                    ->get()->row();
        }
        
        return static::$rows[$table][$row_id];
    }

    protected static function set($id, $object)
    {
        $class = get_called_class();

        if (!isset(self::$objects[$class])) {
            self::$objects[$class] = array();
        }

        if ($object == NULL) {
            unset(self::$objects[$class][$id]);
        }
        else {
            self::$objects[$class][$id] = $object;
        }
    }

    static function create($data = array())
    {
        $class = get_called_class();
        $object = new $class();
        foreach ($data as $property => $value) {
            $object->$property = $value;
        }

        $object->save();
        return self::get($object->id);
    }

    private static function _get_id($conditions)
    {
        $table = static::$table;
        $row = ci()->db->select("$table.id AS id")
                ->from($table)
                ->where($conditions)
                ->get()->row();

        if (empty($row))
            return NULL;

        return $row->id;
    }

    protected $prev_data = array();
    protected $data = array();

    function __construct($id = NULL)
    {
        $this->load($id);
    }

    function get_table()
    {
        return self::$table;
    }

    function exists()
    {
        return !($this->data == FALSE || $this->data['id'] == NULL);
    }

    protected function load($id = NULL)
    {
        if ($id) {
            $table = static::$table;
            $id = intval($id);

            $this->data = static::fetch_row($table, $id);
            if (empty($this->data)) {
                $this->data = FALSE;
                return FALSE;
            }

            $this->data['id'] = $id;
            $this->prev_data = $this->data;
        }
    }

    function values()
    {
        return $this->data;
    }

    function is_new()
    {
        return $this->id == NULL;
    }

    private $just_created = FALSE;

    function just_created()
    {
        return $this->just_created;
    }

    function save()
    {
        $this->before_save();
        if ($this->is_new()) {
            $this->insert();
            $this->just_created = TRUE;
            self::set($this->id, $this); //set the cache
        }
        else {
            $this->update();
        }
    }

    function delete()
    {
        if ($this->is_new())
            return;

        $this->db()->delete(static::$table, array('id' => $this->id));

        self::set($this->id, NULL);

        $this->data = FALSE;
    }

    function changes()
    {
        $changes = array();
        foreach ($this->data as $k => $v) {
            if ($this->data[$k] != $this->prev_data[$k]) {
                $changes[$k] = $this->data[$k];
            }
        }
        unset($changes['id']);

        return $changes;
    }

    function changed()
    {
        $changes = $this->changes();
        return !empty($changes);
    }

    private function insert()
    {
        $data = $this->data;
        unset($data['id']);
        $this->db()->insert(static::$table, $data);

        $this->load($this->db()->insert_id()); //load values
    }

    private function update()
    {
        $changes = $this->changes();

        if (empty($changes))
            return;

        $this->db()->update(static::$table, $this->changes(), array('id' => $this->id));

        $this->load($this->id); //refresh values
    }


    function __get($name)
    {
        $method = "get_$name";
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return isset($this->data[$name]) ? $this->data[$name] : NULL;
    }

    function __set($name, $value)
    {
        if ($name == 'id')
            throw new Exception("The id property is read-only.");

        $this->data[$name] = $value;
    }

    function __isset($name)
    {
        return isset($this->data[$name]);
    }

    function __unset($name)
    {
        unset($this->data[$name]);
    }

    function db()
    {
        return ci()->db;
    }

    function load_objects($class, $rows)
    {
        if (is_string($rows)) {
            $rows = $this->db()->query($rows);
        }

        if ($rows instanceof CI_DB_mysql_driver) {
            $rows = $rows->get()->result();
        }
        elseif ($rows instanceof CI_DB_mysql_result) {
            $rows = $rows->result();
        }

        //pre-fetch all of the table data
        $ids = array();
        foreach ($rows as $row) {
            $ids[] = $row->id;
        }
        static::pre_fetch_rows($class::$table, $ids);

        $objects = array();
        foreach ($rows as $row) {
            $objects[] = $class::get($row->id);
        }
        return $objects;
    }

    protected function before_save()
    {
    }

}
