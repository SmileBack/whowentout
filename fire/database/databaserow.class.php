<?php

class DatabaseRow
{

    private $values = array();
    private $changes = array();

    /**
     * @var DatabaseTable
     */
    private $table;

    function __construct(DatabaseTable $table, $id)
    {
        $this->table = $table;
        $this->load_values($id);
    }

    function values()
    {
        return $this->values;
    }

    /**
     * @return DatabaseTable
     */
    function table()
    {
        return $this->table;
    }

    /**
     * @return Database
     */
    function database()
    {
        return $this->table()->database();
    }

    /**
     * @param  $name
     * @return DatabaseColumn
     */
    function column($name)
    {
        return $this->table->column($name);
    }

    function changes()
    {
        return $this->changes;
    }

    function __get($field)
    {
        if (isset($this->changes[$field]))
            $value = $this->changes[$field];
        elseif (isset($this->values[$field]))
            $value = $this->values[$field];
        else
            return $this->resolve_reference($field);

        $converted_value = $this->column($field)->from_database_value($value);
        return $converted_value;
    }

    function __set($field, $value)
    {
        $converted_value = $this->column($field)->to_database_value($value);
        $this->changes[$field] = $converted_value;
    }

    function resolve_reference($field)
    {
        $fk_column = $field . '_id';
        if ($this->table()->has_column($fk_column) && $this->table()->has_foreign_key($fk_column)) {
            $fk_id = $this->values[$fk_column];
            if ($fk_id) {
                $table_name = $this->table()->get_foreign_key_table_name($fk_column);
                return $this->table()->database()->table($table_name)->row($fk_id);
            }
        }

        return null;
    }
    
    function save()
    {
        $id_column = $this->table()->id_column()->name();
        $changes = $this->changes();
        $this->table->_persist_row_changes($this->$id_column, $changes);
    }
    
    private function load_values($row_id)
    {
        $this->values = $this->table->_fetch_row_values($row_id);
        if (!$this->values)
            throw new Exception("Row with id $row_id doesn't exist.");
    }

}
