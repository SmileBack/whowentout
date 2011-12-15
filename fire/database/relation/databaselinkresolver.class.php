<?php

class DatabaseLinkResolver
{
    
    /**
     * @param DatabaseTable $left_table
     * @param $field_name
     * @return DatabaseLinkPath
     */
    function resolve_link_path(DatabaseTable $left_table, $field_name)
    {
        $path = new DatabaseLinkPath();

        $field_parts = explode('.', $field_name);
        $current_left_table = $left_table;

        foreach ($field_parts as $current_field_part) {
            $link = $this->resolve_link($current_left_table, $current_field_part);
            
            if (!$link)
                return false;
                
            elseif ($link instanceof DatabaseTableLink)
                $current_left_table = $link->right_table;
            
            $path->add_link($link);
        }
        
        return $path;
    }

    /**
     * @param DatabaseTable $left_table
     * @param  $field_name
     * @return DatabaseLink|null
     */
    function resolve_link(DatabaseTable $left_table, $field_name)
    {
        if ($left_table->has_column($field_name)) {
            return new DatabaseColumnLink($left_table, $left_table->column($field_name));
        }

        elseif ($left_table->has_column($field_name . '_id')) {
            $left_column = $left_table->column($field_name . '_id');
            $right_column = $left_table->get_foreign_key_column($field_name . '_id');
            return new DatabaseTableLink($left_column, $right_column);
        }

        elseif ($left_table->database()->has_table($field_name)) {
            $right_table = $left_table->database()->table($field_name);
            $right_column_name = Inflect::singularize($left_table->name()) . '_id';
            $right_column = $right_table->column($right_column_name);
            $left_column = $right_table->get_foreign_key_column($right_column_name);
            return new DatabaseTableLink($left_column, $right_column);
        }

        else {
            return null;
        }
    }
    
}
