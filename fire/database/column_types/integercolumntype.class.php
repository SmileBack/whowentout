<?php

class IntegerColumnType extends ColumnType
{

    function to_sql()
    {
        $sql = array();

        $sql[] = 'INTEGER';
        if ( ! isset($this->options['unsigned']) || $this->options['unsigned'] == true) {
            $sql[] = 'UNSIGNED';
        }

        return implode(' ', $sql);
    }

}
