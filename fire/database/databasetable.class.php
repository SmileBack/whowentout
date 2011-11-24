<?php

class DatabaseTable
{

    private $db;
    private $name;

    private $schema;
    private $rows = array();
    private $columns = array();

    function __construct(Database $db, $table_name)
    {
        $this->db = $db;
        $this->_load_schema_from_database($table_name);
        $this->cache = new Cache(array(
                                      'driver' => 'array'
                                 ));
    }

    function name()
    {
        return $this->name;
    }

    /**
     * @param  $id
     * @return DatabaseRow|null
     */
    function row($id)
    {
        if (!$this->_row_exists($id))
            return null;
        
        if (!isset($this->rows[$id])) {
            $this->rows[$id] = new DatabaseRow($this, $id);
        }

        return $this->rows[$id];
    }

    /**
     * @param array $values
     * @return DatabaseRow
     */
    function create_row($values = array())
    {
        $query = $this->create_row_query($values);
        $query->execute();

        $row_id = $this->db->last_insert_id();
        return $this->row($row_id);
    }

    function destroy_row($id)
    {
        $query = $this->destroy_row_query($id);
        $query->execute();

        unset($this->rows[$id]);
    }

    /**
     * @param  $name
     * @return DatabaseColumn
     */
    function column($name)
    {
        if (!isset($this->schema['columns'][$name]))
            return NULL;

        if (!isset($this->columns[$name])) {
            $column_options = $this->schema['columns'][$name];
            $column_type = $column_options['type'];
            $column = f()->class_loader()->init_subclass('databasecolumn', $column_type, $this, $column_options);

            $this->columns[$name] = $column;
        }

        return $this->columns[$name];
    }

    function create_column($name, array $options)
    {
        $column_sql = $this->get_column_sql($name, $options);
        $query = $this->db->query_statement("ALTER TABLE $this->name ADD $column_sql");
        $query->execute();

        $this->_refresh_schema();
    }

    function rename_column($name, $new_name)
    {
    }

    function destroy_column($name)
    {
        $query = $this->db->query_statement("ALTER TABLE $this->name DROP COLUMN $name");
        $query->execute();

        $this->_refresh_schema();
        unset($this->columns[$name]);
    }

    function create_index($column)
    {
    }

    function create_unique_index($column)
    {
    }

    function destroy_index($column)
    {
    }

    function _refresh_schema()
    {
        $this->_load_schema_from_database($this->name);
    }

    function _load_schema_from_database($table_name)
    {
        $this->name = $table_name;

        $schema = array(
            'name' => $table_name,
            'columns' => array(),
            'primary key' => array(),
        );

        $query = $this->db->query_statement("SHOW FULL COLUMNS FROM {$this->name}");
        $query->execute();
        $column_infos = $query->fetchAll(PDO::FETCH_OBJ);

        foreach ($column_infos as $info) {
            $column = array(
                'name' => $info->Field,
                'type' => $this->get_data_type($info),
                'null' => ($info->Null == 'YES'),
                'description' => $info->Comment,
            );

            $column['default'] = $info->Default;

            //size of field
            $size = $this->get_mysql_data_size($info);
            if ($size !== false) {
                $column['size'] = $size;
            }

            $schema['columns'][$column['name']] = $column;
        }

        $query = $this->db->query_statement("SHOW INDEX FROM {$this->name}");
        $query->execute();
        $index_infos = $query->fetchAll(PDO::FETCH_OBJ);

        foreach ($index_infos as $info) {
            if ($info->Key_name == 'PRIMARY') {
                $schema['primary key'][] = $info->Column_name;
            }
            elseif ($info->Non_unique == '0') { // means unique
                $schema['unique keys'][$info->Key_name][] = $info->Column_name;
            }
            elseif ($info->Non_unique == '1') { // not unique.. just an index
                $schema['indexes'][$info->Key_name][] = $info->Column_name;
            }
        }

        $this->schema = $schema;
    }

    private function get_data_type($field_info)
    {
        $mysql_type = $this->get_mysql_data_type($field_info);
        $mysql_size = $this->get_mysql_data_size($field_info);

        if ($mysql_type == 'tinyint' && $mysql_size == 1)
            return 'boolean';
        elseif ($mysql_type == 'varchar')
            return 'string';
        elseif ($mysql_type == 'int')
            return 'integer';
        elseif ($mysql_type == 'text')
            return 'text';
        elseif ($mysql_type == 'datetime')
            return 'time';
    }

    private function get_mysql_data_type($field_info)
    {
        $matches = array();
        $num_matches = preg_match('/^(?<type>\w+)/', $field_info->Type, $matches);
        return $num_matches > 0 ? $matches['type'] : false;
    }

    private function get_mysql_data_size($field_info)
    {
        $matches = array();
        $num_matches = preg_match('/^\w+\((?<size>\d+)\)/', $field_info->Type, $matches);
        return $num_matches > 0 ? (int)$matches['size'] : false;
    }

    private function update_row_query($id, array $values)
    {
        $columns = array_keys($values);
        $set_statements = array();
        foreach ($columns as $column) {
            $set_statements[] = "$column = :$column";
        }

        $set_statements = implode(', ', $set_statements);

        $sql = "UPDATE $this->name SET $set_statements\n";
        $sql .= " WHERE id = :id";

        $values['id'] = $id;

        return $this->db->query_statement($sql, $values);
    }

    private function create_row_query(array $values)
    {
        $columns = array_keys($values);

        $columns_sql = implode(',', $columns);

        $values_sql = array();
        foreach ($columns as $column) {
            $values_sql[] = ":$column";
        }
        $values_sql = implode(', ', $values_sql);

        $sql = "INSERT INTO $this->name ($columns_sql) VALUES ($values_sql)";
        return $this->db->query_statement($sql, $values);
    }

    private function destroy_row_query($row_id)
    {
        $sql = "DELETE FROM $this->name WHERE id = :id";
        return $this->db->query_statement($sql, array('id' => $row_id));
    }

    private function get_column_sql($column_name, $column_config)
    {
        $column_type = f()->class_loader()->init_subclass('columntype', $column_config['type'], $column_config);
        return $column_name . ' ' . $column_type->to_sql();
    }

    function _persist_row_changes($row_id, $changes)
    {
        $query = $this->update_row_query($row_id, $changes);
        $query->execute();
    }

    function _fetch_row_values($row_id)
    {
        $query = $this->db->query_statement("SELECT * FROM $this->name WHERE id = :id", array(':id' => $row_id));
        $query->execute();
        $row_count = $query->rowCount();
        return $row_count > 0 ? (array)$query->fetchObject() : false;
    }

    function _row_exists($row_id)
    {
        return $this->_fetch_row_values($row_id) != null;
    }
    
}
