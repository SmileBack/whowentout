<?php

class DatabaseFilter extends QueryPart
{

    /* @var $base_base DatabaseTable */
    private $base_table;

    /* @var $field_name string */
    private $field_name;

    /* @var $field_value mixed */
    private $field_value;

    /* @var $field DatabaseField */
    private $field;

    private $unique_id;

    function __construct(DatabaseTable $base_table, $field_name, $field_value)
    {
        $this->base_table = $base_table;
        $this->field_name = $field_name;
        $this->field_value = $field_value;

        $this->unique_id = uniqid('field__');

        $this->compute();
    }

    function compute()
    {
        $this->field = new DatabaseField($this->base_table, $this->field_name);
    }

    function to_sql()
    {
        $filter_placeholder = $this->get_filter_placeholder();
        return $this->field->to_sql() . " = :$filter_placeholder";
    }

    function parameters()
    {
        $params = array();

        $column = $this->field->column();
        $filter_placeholder = $this->get_filter_placeholder();
        $database_value = $column->to_database_value($this->field_value);
        
        $params[$filter_placeholder] = $database_value;

        return $params;
    }
    
    /**
     * @return DatabaseTableJoin[]
     */
    function joins()
    {
        return $this->field->joins();
    }
    
    private function get_filter_placeholder()
    {
        return $this->unique_id;
    }

    /**
     * @return DatabaseColumn
     */
    private function get_field_column()
    {
        return $this->field->column();
    }

}
