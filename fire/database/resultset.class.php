<?php

class ResultSet
{

    private $filters = array();
    private $limit = false;
    private $order_by = false;

    /**
     * @var DatabaseTable
     */
    private $tables;

    function __construct(DatabaseTable $table)
    {
        $this->tables[] = $table;
    }

    function where($column, $value)
    {
        $this->filters[$column] = $value;
        return $this;
    }

    function order_by($column, $order = 'asc')
    {
        $allowed_order = array('asc', 'desc');

        $order = strtolower($order);
        if (!in_array($order, $allowed_order))
            throw new Exception("\$order parameter must be asc or desc");

        $this->order_by = array(
            'column' => $column,
            'order' => $order,
        );

        return $this;
    }

    function limit($n)
    {
        $this->limit = intval($n);
        return $this;
    }

    function to_sql()
    {
        $params = array();
        $sql = array();

        $sql[] = $this->get_select_from_tables_sql();

        if (count($this->filters) > 0)
            $sql[] = "\n  " . $this->get_where_sql();

        if ($this->order_by)
            $sql[] = "\n  " . $this->get_order_by_sql();

        if ($this->limit)
            $sql[] = "\n  " . $this->get_limit_sql();

        $params = array_merge($params, $this->get_where_parameters());

        return implode('', $sql);
    }

    /**
     * @return DatabaseTable
     */
    private function table()
    {
        return $this->tables[0];
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
        foreach ($this->filters as $column => $value) {
            $filter_placeholder = $this->get_filter_placeholder($column);
            $sql[] = "$table_name.$column = :$filter_placeholder";
        }
        return "WHERE " . implode(' AND ', $sql);
    }

    private function get_order_by_sql()
    {
        $table_name = $this->table()->name();
        $order_by_column = $this->order_by['column'];
        $order_by_order = $this->order_by['order'];

        return "ORDER BY $table_name.$order_by_column $order_by_order";
    }

    private function get_limit_sql()
    {
        return "LIMIT $this->limit";
    }

    private function get_where_parameters()
    {
        $params = array();
        foreach ($this->filters as $column_name => $column_value) {
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
