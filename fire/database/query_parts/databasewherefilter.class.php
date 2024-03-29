<?php

class DatabaseWhereFilter extends QueryPart
{

    /* @var $field DatabaseField */
    public $field;

    public $value;

    private $unique_id;

    function __construct(DatabaseField $field, $value)
    {
        $this->field = $field;
        $this->value = $value;
        
        $this->unique_id = uniqid('field__');
    }

    function __clone()
    {
        $this->field = clone $this->field;
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
        $this->field->column();
        $filter_placeholder = $this->get_filter_placeholder();
        $database_value = $column->to_database_value($this->value);
        
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
    
    private function get_filter_placeholder($n = null)
    {
        if (!$n)
            return $this->unique_id;
        else
            return $this->unique_id . '_' . str_pad($n, 4, '0', STR_PAD_LEFT);
    }

    /**
     * @return DatabaseColumn
     */
    private function get_field_column()
    {
        return $this->field->column();
    }

}
