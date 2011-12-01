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
            return null;

        $converted_value = $this->column($field)->from_database_value($value);
        return $converted_value;
    }

    function __set($field, $value)
    {
        if ($field == 'id')
            throw new Exception("The id property is read-only.");

        $converted_value = $this->column($field)->to_database_value($value);
        $this->changes[$field] = $converted_value;
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
