<?php

class ResultSet implements Iterator
{
    
    /**
     * @var DatabaseTable
     */
    private $base_table;

    /**
     * @var DatabaseFilter[]
     */
    private $filters = array();

    /**
     * @var DatabaseLimit|null
     */
    private $limit = null;

    /**
     * @var DatabaseOrderBy|null
     */
    private $order_by = null;

    function __construct(DatabaseTable $base_table)
    {
        $this->base_table = $base_table;
    }

    /**
     * @param  $field_name
     * @param  $field_value
     * @return ResultSet
     */
    function where($field_name, $field_value)
    {
        $set = clone $this;
        $set->set_where($field_name, $field_value);
        return $set;
    }

    function set_where($field_name, $field_value)
    {
        $this->filters[] = new DatabaseFilter($this->base_table, $field_name, $field_value);
    }

    /**
     * @param  $field_name
     * @param string $order
     * @return ResultSet
     */
    function order_by($field_name, $order = 'asc')
    {
        $set = clone $this;
        $set->set_order_by($field_name, $order);
        return $set;
    }

    function set_order_by($field_name, $order = 'asc')
    {
        $this->order_by = new DatabaseOrderBy($this->base_table, $field_name, $order);
    }

    /**
     * @param  $n
     * @return ResultSet
     */
    function limit($n)
    {
        $set = clone $this;
        $set->set_limit($n);
        return $set;
    }

    function set_limit($n)
    {
        $this->limit = new DatabaseLimit($n);
    }

    function to_sql()
    {
        $sql = array();

        $sql[] = "SELECT " . $this->base_table->name() . '.' . $this->base_table->id_column()->name()
                           . ' AS id FROM ' . $this->base_table->name();

        foreach ($this->joins() as $join) {
            $sql[] = "\n  " . $join->to_sql();
        }

        if (count($this->filters) > 0)
            $sql[] = "\n  " . $this->get_where_sql();
        
        if ($this->order_by)
            $sql[] = "\n  " . $this->order_by->to_sql();

        if ($this->limit)
            $sql[] = "\n  " . $this->limit->to_sql();
        
        return implode('', $sql);
    }

    function to_delete_sql()
    {
        $sql = array();

        $sql[] = $this->get_delete_from_table_sql();

        if (count($this->query['filters']) > 0)
            $sql[] = "\n  " . $this->get_where_sql();

        if ($this->query['limit'])
            $sql[] = "\n  " . $this->get_limit_sql();

        return implode('', $sql);
    }

    function get_parameters()
    {
        $params = array();
        $params = array_merge($params, $this->get_where_parameters());
        return $params;
    }

    /**
     * @return DatabaseRow|null
     */
    function first()
    {
        $set = clone $this;
        $set->limit(1);
        foreach ($set as $item) {
            return $item;
        }
        return null;
    }

    function count()
    {
        $query = $this->database()->query_statement($this->to_sql(), $this->get_parameters());
        $query->execute();
        return $query->rowCount();
    }

    /* Iterator Methods */
    /**
     * @var TableQueryIterator
     */
    private $iterator;

    function current()
    {
        return $this->iterator->current();
    }

    function key()
    {
        return $this->iterator->key();
    }

    function next()
    {
        return $this->iterator->next();
    }

    function rewind()
    {
        $table = $this->table();
        $sql = $this->to_sql();
        $params = $this->get_parameters();
        $this->iterator = new TableQueryIterator($table, $sql, $params);

        $this->iterator->rewind();
    }

    function valid()
    {
        return $this->iterator->valid();
    }

    function destroy()
    {
        foreach ($this as $row_id => $row) {
            $this->table()->destroy_row($row_id);
        }
    }

    /**
     * @return DatabaseTable
     */
    function table()
    {
        return $this->query['base_table'];
    }

    /**
     * @return Database
     */
    function database()
    {
        return $this->table()->database();
    }

    /**
     * @return DatabaseTableJoin[]
     */
    private function joins()
    {
        $joins = array();
        foreach ($this->filters as $filter) {
            foreach ($filter->joins() as $join) {
                $joins[ $join->join_table->name() ] = $join;
            }
        }
        
        if ($this->order_by) {
            foreach ($this->order_by->joins() as $join) {
                $joins[ $join->join_table->name() ] = $join;
            }
        }

        return $joins;
    }

    private function get_select_from_tables_sql()
    {
        $table_name = $this->table()->name();
        $id_column_name = $this->table()->id_column()->name();
        return "SELECT $table_name.$id_column_name AS id FROM $table_name";
    }

    private function get_delete_from_table_sql()
    {
        $table_name = $this->table()->name();
        return "DELETE FROM $table_name";
    }

    private function get_where_sql()
    {
        $sql = array();
        
        foreach ($this->filters as $filter) {
            $sql[] = $filter->to_sql();
        }

        return "WHERE " . implode(' AND ', $sql);
    }

    private function get_order_by_sql()
    {
        $table_name = $this->table()->name();
        $order_by_column = $this->query['order_by']['column'];
        $order_by_order = $this->query['order_by']['order'];

        return "ORDER BY $table_name.$order_by_column $order_by_order";
    }

    private function get_limit_sql()
    {
        return "LIMIT $this->limit";
    }

    private function get_where_parameters()
    {
        $params = array();
        foreach ($this->query['filters'] as $column_name => $column_value) {
            $filter_placeholder = $this->get_filter_placeholder($column_name);
            $database_value = $this->get_database_value($column_name, $column_value);
            $params[$filter_placeholder] = $database_value;
        }
        return $params;
    }

    private function get_database_value($column_name, $column_value)
    {
        $column = $this->table()->column($column_name);
        if (!$column)
            throw new Exception("Column '$column_name' is missing.");

        return $column->to_database_value($column_value);
    }

    private function get_filter_placeholder($column)
    {
        return 'filter__' . $this->table()->name() . '__' . $column;
    }
    
}
