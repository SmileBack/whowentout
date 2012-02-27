<?php

class DatabaseWhereFilter extends QueryPart
{

    /* @var $field DatabaseField */
    public $field;

    public $value;

    private $unique_id;

    private static function unique_field_name()
    {
        static $n = 0;
        $n++;
        return "field__$n";
    }

    function __construct(DatabaseField $field, $value)
    {
        $this->field = $field;
        $this->value = $value;

        $this->unique_id = static::unique_field_name();
    }

    function __clone()
    {
        $this->field = clone $this->field;
    }
    
    function to_sql()
    {
        if (is_array($this->value))
            return $this->to_sql_in();
        else
            return $this->to_sql_equals();
    }

    private function to_sql_equals()
    {
        $filter_placeholder = $this->get_filter_placeholder();
        return $this->field->to_sql() . " = :$filter_placeholder";
    }

    private function to_sql_in()
    {
        $values = array();
        $n = 1;
        foreach ($this->value as $k => $v) {
            $values[] = ':' . $this->get_filter_placeholder($n);
            $n++;
        }
        $values_sql = implode(',', $values);

        $sql = $this->field->to_sql() . " IN ($values_sql)";
        return $sql;
    }

    function parameters()
    {
        if (is_array($this->value))
            return $this->parameters_in();
        else
            return $this->parameters_equals();
    }

    private function parameters_equals()
    {
        $params = array();

        $column = $this->field->column();
        $filter_placeholder = $this->get_filter_placeholder();
        $database_value = $column->to_database_value($this->value);

        $params[$filter_placeholder] = $database_value;

        return $params;
    }

    private function parameters_in()
    {
        $params = array();

        $column = $this->field->column();

        $n = 1;
        foreach ($this->value as $k => $v) {
            $placeholder = $this->get_filter_placeholder($n);
            $params[$placeholder] = $column->to_database_value($v);
            $n++;
        }

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
