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

    function __construct(DatabaseTable $base_table, $field_name, $field_value)
    {
        $this->base_table = $base_table;
        $this->field_name = $field_name;
        $this->field_value = $field_value;

        $this->compute();
    }

    function compute()
    {
        $this->field = new DatabaseField($this->base_table, $this->field_name);
    }

    function to_sql()
    {
        return $this->field->to_sql() . ' = ' . '[woo]';
    }

    /**
     * @return DatabaseTableJoin[]
     */
    function joins()
    {
        return $this->field->joins();
    }
    
}