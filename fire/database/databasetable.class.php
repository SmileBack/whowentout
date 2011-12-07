<?php

class DatabaseTable implements Iterator
{

    /**
     * @var \Database
     */
    private $db;
    
    private $name;

    private $schema;
    private $rows = array();
    public $columns = array();

    function __construct(Database $db, $table_name)
    {
        $this->db = $db;
        $this->_load_schema_from_database($table_name);
    }

    function name()
    {
        return $this->name;
    }

    /**
     * @return Database
     */
    function database()
    {
        return $this->db;
    }

    /**
     * @param  $id
     * @return DatabaseRow|null
     */
    function row($id)
    {
        if (!$this->row_exists($id))
            return null;

        if (!isset($this->rows[$id])) {
            $this->rows[$id] = new DatabaseRow($this, $id);
        }

        return $this->rows[$id];
    }

    /**
     * @param  $row_id
     * @return bool
     */
    function row_exists($row_id)
    {
        return $this->_fetch_row_values($row_id) != null;
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

    function count()
    {
        $query = $this->database()->query_statement('SELECT COUNT(*) AS count FROM ' . $this->name());
        $query->execute();
        $count = $query->fetch(PDO::FETCH_COLUMN);
        return intval($count);
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
            $column = app()->class_loader()->init_subclass('databasecolumn', $column_type, $this, $column_options);
            $this->columns[$name] = $column;
        }

        return $this->columns[$name];
    }

    /**
     * @return DatabaseColumn
     */
    function id_column()
    {
        assert(count($this->schema['primary_key']) == 1);
        $pk = $this->schema['primary_key'][0];
        return $this->column($pk);
    }

    function has_column($column_name)
    {
        return $this->column($column_name) != null;
    }

    function create_column($name, array $options)
    {
        $column_sql = $this->get_column_sql($name, $options);
        $query = $this->db->query_statement("ALTER TABLE $this->name ADD $column_sql");
        $query->execute();

        $this->_refresh_schema();

    }

    function rename_column($current_column_name, $new_column_name)
    {
        /* @var $column DatabaseColumn */
        $column = $this->columns[$current_column_name];

        $table_name = $this->name();
        $create_table_schema = $this->fetch_create_table_schema();
        $column_schema = $create_table_schema['columns'][$current_column_name]['schema'];
        $query = $this->db->query_statement("ALTER TABLE $table_name CHANGE $current_column_name $new_column_name $column_schema");
        $query->execute();

        $this->_refresh_schema();

        // change name on object
        unset($this->columns[$current_column_name]);
        $column->_set_name($new_column_name);
        $this->columns[$new_column_name] = $column;
    }

    function destroy_column($column_name)
    {
        if ($this->has_foreign_key($column_name))
            $this->destroy_foreign_key($column_name);

        $query = $this->db->query_statement("ALTER TABLE $this->name DROP COLUMN $column_name");
        $query->execute();

        $this->_refresh_schema();
        unset($this->columns[$column_name]);
    }

    function create_index($column)
    {
        $column_list = func_get_args();
        $column_list_sql = implode(', ', $column_list);
        $table_name = $this->name();
        $index_name = $this->get_index_name($column_list);

        $query = $this->db->query_statement("CREATE INDEX `$index_name` ON $table_name ($column_list_sql) USING BTREE");
        $query->execute();
    }

    function destroy_index($column)
    {
        $column_list = func_get_args();
        $table_name = $this->name();
        $index_name = $this->get_index_name($column_list);

        $query = $this->db->query_statement("DROP INDEX `$index_name` ON $table_name");
        $query->execute();
    }

    function has_index($column)
    {
        $column_list = func_get_args();
        
        $query_sql = "SELECT * FROM information_schema.statistics
                        WHERE index_schema = :database_name
                        AND table_name = :table_name
                        AND index_name = :index_name";
        
        $params = array(
            'database_name' => $this->database()->name(),
            'table_name' => $this->name(),
            'index_name' => $this->get_index_name($column_list),
        );

        $query = $this->db->query_statement($query_sql, $params);
        $query->execute();
        
        return $query->rowCount() > 0;
    }

    function create_unique_index($column)
    {
        throw new Exception("Not yet implemented.");
    }

    private function get_index_name(array $columns)
    {
        $columns = array_map('strtolower', $columns);
        sort($columns, SORT_STRING);

        $index_name = implode(':', $columns);
        return $index_name;
    }

    function create_foreign_key($column, $referenced_table, $referenced_column)
    {
        $table_name = $this->name();
        $foreign_key_name = $this->get_foreign_key_name($column);

        if (!$this->database()->has_table($referenced_table))
            throw new TableMissingException();

        $query_sql = "ALTER TABLE $table_name
                        ADD CONSTRAINT `$foreign_key_name` FOREIGN KEY ($column) REFERENCES $referenced_table ($referenced_column)";
        $query = $this->db->query_statement($query_sql);
        $query->execute();

        $this->_refresh_schema();
    }

    function has_foreign_key($column)
    {
        $key_name = $this->get_foreign_key_name($column);
        return isset($this->schema['foreign_keys'][$key_name]);
    }

    function destroy_foreign_key($column)
    {
        if ($this->has_foreign_key($column)) {
            $table_name = $this->name();
            $foreign_key_name = $this->get_foreign_key_name($column);
            $query = $this->db->query_statement("ALTER TABLE $table_name DROP FOREIGN KEY `$foreign_key_name`");
            $query->execute();
            $this->_refresh_schema();
        }
    }

    function get_foreign_key_table($column)
    {
        if ($this->has_foreign_key($column)) {
            $key_name = $this->get_foreign_key_name($column);
            return $this->schema['foreign_keys'][$key_name]['referenced_table'];
        }
        return null;
    }

    function get_foreign_key_column($column)
    {
        if ($this->has_foreign_key($column)) {
            $key_name = $this->get_foreign_key_name($column);
            return $this->schema['foreign_keys'][$key_name]['referenced_column'];
        }
        return null;
    }

    private function get_foreign_key_name($column)
    {
        $table_name = $this->name();
        return "$table_name.$column";
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
            'primary_key' => array(),
        );

        // we must fetch schema from two sources because the information schema table is incomplete
        $information_schema = $this->fetch_mysql_information_schema();
        $create_table_schema = $this->fetch_create_table_schema();

        $schema['foreign_keys'] = $create_table_schema['foreign_keys'];
        $schema['indexes'] = $create_table_schema['indexes'];

        foreach ($information_schema as $column_info) {
            $column = array(
                'name' => $column_info->Field,
                'type' => $this->get_data_type($column_info),
                'null' => ($column_info->Null == 'YES'),
                'description' => $column_info->Comment,
            );

            $column['default'] = $column_info->Default;

            $column['primary_key'] = $column_info->Key == 'PRI';
            $column['auto_increment'] = $column_info->Extra == 'auto_increment';

            //size of field
            $size = $this->get_mysql_data_size($column_info);
            if ($size !== false) {
                $column['size'] = $size;
            }

            $schema['columns'][$column['name']] = $column;
        }

        $query = $this->db->query_statement("SHOW INDEX FROM {$this->name}");
        $query->execute();
        $index_infos = $query->fetchAll(PDO::FETCH_OBJ);

        foreach ($index_infos as $column_info) {
            if ($column_info->Key_name == 'PRIMARY') {
                $schema['primary_key'][] = $column_info->Column_name;
            }
            elseif ($column_info->Non_unique == '0') { // means unique
                $schema['unique_keys'][$column_info->Key_name][] = $column_info->Column_name;
            }
            elseif ($column_info->Non_unique == '1') { // not unique.. just an index
                $schema['indexes'][$column_info->Key_name][] = $column_info->Column_name;
            }
        }

        $this->schema = $schema;
    }

    /**
     * @param  $column string
     * @param  $value mixed
     * @return ResultSet
     */
    function where($column, $value)
    {
        $result_set = new ResultSet($this);
        $result_set->where($column, $value);
        return $result_set;
    }


    /* Methods */
    private $iterator_position;
    private $iterator_row_ids;

    function current()
    {
        $key = $this->key();
        if ($key)
            return $this->row($key);
    }

    function key()
    {
        if (!$this->valid())
            return null;
        
        return $this->iterator_row_ids[$this->iterator_position];
    }

    function next()
    {
        $this->iterator_position++;
    }

    function rewind()
    {
        $id_column_name = $this->id_column()->name();
        $table_name = $this->name();

        $query = $this->db->query_statement("SELECT $id_column_name AS id FROM $table_name");
        $query->execute();

        $this->iterator_row_ids = $query->fetchAll(PDO::FETCH_COLUMN, 0);
        $this->iterator_position = 0;
    }

    function valid()
    {
        return $this->iterator_row_ids
               && isset($this->iterator_row_ids[$this->iterator_position]);
    }

    private function fetch_create_table_schema()
    {
        $query = $this->db->query_statement("SHOW CREATE TABLE {$this->name}");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        $create_table_statement = $result['Create Table'];
        $parser = new CreateTableParser();
        return $parser->parse($create_table_statement);
    }

    private function fetch_mysql_information_schema()
    {
        $query = $this->db->query_statement("SHOW FULL COLUMNS FROM {$this->name}");
        $query->execute();
        $column_schemas = $query->fetchAll(PDO::FETCH_OBJ);
        return $column_schemas;
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
        elseif ($mysql_type == 'date')
            return 'date';
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
        $id_column = $this->id_column()->name();

        $columns = array_keys($values);
        $set_statements = array();
        foreach ($columns as $column) {
            $set_statements[] = "$column = :$column";
        }

        $set_statements = implode(', ', $set_statements);

        $sql = "UPDATE $this->name SET $set_statements\n";
        $sql .= " WHERE $id_column = :id";

        $values['id'] = $id;

        return $this->db->query_statement($sql, $values);
    }

    private function create_row_query(array $values)
    {
        $values = $this->format_values_for_database($values);

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

    private function format_values_for_database($values)
    {
        foreach ($values as $column => &$value) {
            $value = $this->column($column)->to_database_value($value);
        }
        return $values;
    }

    private function destroy_row_query($row_id)
    {
        $id_column = $this->id_column()->name();
        $sql = "DELETE FROM $this->name WHERE $id_column = :id";
        return $this->db->query_statement($sql, array('id' => $row_id));
    }

    private function get_column_sql($column_name, $column_config)
    {
        $column_type = app()->class_loader()->init_subclass('columntype', $column_config['type'], $column_config);
        return $column_name . ' ' . $column_type->to_sql();
    }

    function _persist_row_changes($row_id, $changes)
    {
        $query = $this->update_row_query($row_id, $changes);
        $query->execute();
    }

    function _fetch_row_values($row_id)
    {
        $id_column = $this->id_column()->name();
        $query = $this->db->query_statement("SELECT * FROM $this->name WHERE $id_column = :id", array('id' => $row_id));
        $query->execute();
        $row_count = $query->rowCount();
        return $row_count > 0 ? (array)$query->fetchObject() : false;
    }

}
