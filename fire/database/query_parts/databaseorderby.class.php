<?php

class DatabaseOrderBy extends QueryPart
{

    private $allowed_orders = array('asc', 'desc');

    /* @var $base_table DatabaseField*/
    private $field;
    private $order;
    

    function __construct(DatabaseField $field, $order = 'asc')
    {
        $this->field = $field;
        $this->order = strtolower($order);
        $this->validate_order();
    }

    function to_sql()
    {
        return "ORDER BY " . $this->field->to_sql() . ' ' . $this->order;
    }

    private function validate_order()
    {
        if (!in_array($this->order, $this->allowed_orders))
            throw new Exception("\$order parameter must be asc or desc");
    }
    
}
