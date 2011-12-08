<?php

class ResultSet implements Iterator
{

    private $query = array(
        'filters' => array(),
        'limit' => false,
        'order_by' => false,
    );

    /**
     * @var DatabaseTable
     */
    private $tables;

    function __construct(DatabaseTable $table)
    {
        $this->tables[] = $table;
    }

    /**
     * @param  $column
     * @param  $value
     * @return ResultSet
     */
    function where($column, $value)
    {
        $set = clone $this;
        $set->set_where($column, $value);
        return $set;
    }

    function set_where($column, $value)
    {
        $this->query['filters'][$column] = $value;
    }

    /**
     * @param  $column
     * @param string $order
     * @return ResultSet
     */
    function order_by($column, $order = 'asc')
    {
        $set = clone $this;
        $set->set_order_by($column, $order);
        return $set;
    }

    function set_order_by($column, $order = 'asc')
    {
        $allowed_order = array('asc', 'desc');

        $order = strtolower($order);
        if (!in_array($order, $allowed_order))
            throw new Exception("\$order parameter must be asc or desc");

        $this->query['order_by'] = array(
            'column' => $column,
            'order' => $order,
        );
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
        $this->query['limit'] = intval($n);
    }

    function to_sql()
    {
        $sql = array();

        $sql[] = $this->get_select_from_tables_sql();

        if (count($this->query['filters']) > 0)
            $sql[] = "\n  " . $this->get_where_sql();

        if ($this->query['order_by'])
            $sql[] = "\n  " . $this->get_order_by_sql();

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

    /**
     * @return DatabaseTable
     */
    function table()
    {
        return $this->tables[0];
    }

    /**
     * @return Database
     */
    function database()
    {
        return $this->table()->database();
    }

    private function get_select_from_tables_sql()
    {
        $table_name = $this->table()->name();
        $id_column_name = $this->table()->id_column()->name();
        return "SELECT $table_name.$id_column_name AS id FROM $table_name";
    }

    private function get_where_sql()
    {
        $sql = array();
        $table_name = $this->table()->name();
        foreach ($this->query['filters'] as $column => $value) {
            $filter_placeholder = $this->get_filter_placeholder($column);
            $sql[] = "$table_name.$column = :$filter_placeholder";
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

    function _set_query(array $query)
    {
        $this->query = $query;
    }

}
