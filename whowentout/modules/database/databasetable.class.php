<?php

class DatabaseTable
{

    private $db;
    private $name;

    function __construct(Database $db, $name)
    {
        $this->db = $db;
        $this->name = $name;

        $this->load_schema_from_database();
    }

    function name()
    {
    }

    function row($id)
    {
    }

    function create_row($values = array())
    {
    }

    function destroy_row($id)
    {
    }

    function column($name)
    {
    }

    function create_column($name, array $options)
    {
    }

    function rename_column($name, $new_name)
    {
    }

    function destroy_column($name)
    {
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

    function update_row_sql($id, array $values)
    {
        $columns = array_keys($values);
        $set_statements = array();
        foreach ($columns as $column) {
            $set_statements[] = "$column = :$column";
        }

        $set_statements = implode(', ', $set_statements);

        $sql = "UPDATE {$this->name()} SET $set_statements\n";
        $sql .= " WHERE id = :id";

        $row['id'] = $id;
        krumo::dump($row);
    }

    function load_schema_from_database()
    {
        $schema = array(
            'name' => $this->name,
            'columns' => array(),
            'primary key' => array(),
        );

        $query = $this->db->query_statement("SHOW FULL COLUMNS FROM {$this->name}");
        $query->execute();
        $column_infos = $query->fetchAll(PDO::FETCH_OBJ);
        krumo::dump($column_infos);

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
            if ($size !== FALSE) {
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

        krumo::dump($this->schema);
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
        return $num_matches > 0 ? $matches['type'] : FALSE;
    }

    private function get_mysql_data_size($field_info)
    {
        $matches = array();
        $num_matches = preg_match('/^\w+\((?<size>\d+)\)/', $field_info->Type, $matches);
        return $num_matches > 0 ? (int)$matches['size'] : FALSE;
    }

}
