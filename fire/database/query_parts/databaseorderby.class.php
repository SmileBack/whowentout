<?php

class DatabaseOrderBy extends QueryPart
{

    private $allowed_orders = array('asc', 'desc');

    /* @var $base_table DatabaseTable */
    private $base_table;
    private $field_name;
    private $order;

    /**
     * @var DatabaseField
     */
    private $field;

    function __construct(DatabaseTable $base_table, $field_name, $order = 'asc')
    {
        $this->base_table;
        $this->field_name = $field_name;
        $this->order = strtolower($order);
        $this->validate_order();
        
        $this->compute();
    }

    function to_sql()
    {
        return "ORDER BY " . $this->field->to_sql() . ' ' . $this->order;
    }

    function joins()
    {
        return $this->field->joins();
    }

    protected function compute()
    {
        $this->field = new DatabaseField($this->base_table, $this->field_name);
    }

    private function validate_order()
    {
        if (!in_array($this->order, $this->allowed_orders))
            throw new Exception("\$order parameter must be asc or desc");
    }
    
}
