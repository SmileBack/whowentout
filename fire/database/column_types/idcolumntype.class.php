<?php

class IdColumnType extends ColumnType
{

    protected $defaults = array(
        'auto_increment' => true,
    );

    function to_sql()
    {
        $sql = array('INTEGER', 'UNSIGNED');

        if ($this->options['auto_increment'])
            $sql[] = 'AUTO_INCREMENT';

        $sql[] = 'PRIMARY KEY';

        $sql[] = 'NOT NULL';

        return implode(' ', $sql);
    }
    
}
