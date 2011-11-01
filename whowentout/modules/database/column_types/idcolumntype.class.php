<?php

class IdColumnType extends ColumnType
{
    function to_sql()
    {
        return 'INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL';
    }
}
