<?php

class DatabaseField extends QueryPart
{

    /* @var DatabaseTable */
    private $base_table;

    /* @var $field_name string */
    private $field_name;

    /* @var $joins DatabaseTableJoin[] */
    private $joins;
    
    /**
     * @var DatabaseColumn
     */
    private $field_column;

    function __construct(DatabaseTable $base_table, $field_name)
    {
        $this->base_table = $base_table;
        $this->field_name = $field_name;
        
        $this->compute();
    }

    /**
     * @return DatabaseColumn
     */
    function column()
    {
        return $this->field_column;
    }

    function to_sql()
    {
        return $this->field_column->table()->name() . '.' . $this->field_column->name();
    }
    
    /**
     * @return DatabaseTableJoin[]
     */
    function joins()
    {
        return $this->joins;
    }
    
    /**
     * @return DatabaseTableJoin[]
     */
    function compute()
    {
        $this->joins = array();

        $field_name_components = explode('.', $this->field_name);

        $current_base_table = $this->base_table;

        foreach ($field_name_components as $current_field_component) {
            // not a relationship, just a regular column
            if ($current_base_table->has_column($current_field_component)) {
                $this->field_column = $current_base_table->column($current_field_component);
            }
            //a relationship to a different table (foreign key)
            else {
                $join = $this->discover_join($current_base_table, $current_field_component);
                if (!$join)
                    throw new Exception("Invalid join. between " . $current_base_table->name() . " and " . $current_field_component);

                $this->joins[] = $join;
                
                $current_base_table = $join->join_table;
            }
        }
    }
    
    /**
     * @param DatabaseTable $base_table
     * @param  $field_name
     * @return DatabaseTableJoin|null
     */
    private function discover_join(DatabaseTable $base_table, $field_name)
    {

        if ($base_table->has_column($field_name . '_id')) {
            $base_column = $base_table->column($field_name . '_id');
            $join_table = $base_table->get_foreign_key_table($field_name . '_id');
            $join_column = $base_table->get_foreign_key_column($field_name . '_id');

            return new DatabaseTableJoin($base_table, $base_column, $join_table, $join_column);
        }
        elseif (db()->has_table($field_name)) {
            $join_table = db()->table($field_name);
            $join_column_name = Inflect::singularize($base_table->name()) . '_id';
            $join_column = $join_table->column($join_column_name);
            $base_column = $join_table->get_foreign_key_column($join_column_name);

            return new DatabaseTableJoin($base_table, $base_column, $join_table, $join_column);
        }
        else {
            return null;
        }
    }
    
}