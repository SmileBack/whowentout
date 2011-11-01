<?php

class IntegerColumnType extends ColumnType
{

    function to_sql()
    {
        $sql = array();

        $sql[] = 'INTEGER';
        if ( ! isset($this->options['unsigned']) || $this->options['unsigned'] == TRUE) {
            $sql[] = 'UNSIGNED';
        }

        return implode(' ', $sql);
    }

}
