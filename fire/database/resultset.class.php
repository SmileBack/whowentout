<?php

class ResultSet
{

    private $filters = array();
    private $limit = false;

    private $order_by = false;
    

    /**
     * @var DatabaseTable
     */
    private $table;

    function __construct(DatabaseTable $table)
    {
        $this->table = $table;
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

    function to_sql()
    {
        $table_name = $this->table->name();
        $id_column_name = $this->table->id_column()->name();

        $params = array();

        $sql = array();
        $sql[] = "SELECT $table_name.$id_column_name AS id FROM $table_name";

        if (count($this->filters) > 0)
            $sql[] = "\n  " . $this->get_where_sql();

        if ($this->order_by)
            $sql[] = "\n  " . $this->get_order_by_sql();

        $params = array_merge($params, $this->get_where_parameters());

        return implode('', $sql);
    }

    private function get_where_sql()
    {
        $sql = array();
        $table_name = $this->table->name();
        foreach ($this->filters as $column => $value) {
            $filter_placeholder = $this->get_filter_placeholder($column);
            $sql[] = "$table_name.$column = :$filter_placeholder";
        }
        return "WHERE " . implode(' AND ', $sql);
    }

    function get_order_by_sql()
    {
        $table_name = $this->table->name();
        $order_by_column = $this->order_by['column'];
        $order_by_order = $this->order_by['order'];

        return "ORDER BY $table_name.$order_by_column $order_by_order";
    }

    private function get_where_parameters()
    {
        $params = array();
        foreach ($this->filters as $column_name => $column_value) {
            $filter_placeholder = $this->get_filter_placeholder($column_name);
            $database_value =$this->get_database_value($column_name, $column_value);
            $params[$filter_placeholder] = $database_value;
        }
        return $params;
    }

    private function get_database_value($column_name, $column_value)
    {
        $column = $this->table->column($column_name);
        if (!$column)
            throw new Exception("Column '$column_name' is missing.");
        
        return $column->to_database_value($column_value);
    }

    private function get_filter_placeholder($column)
    {
        return 'filter__' . $this->table->name() . '__' . $column;
    }

}