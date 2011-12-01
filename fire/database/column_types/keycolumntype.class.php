<?php

class KeyColumnType extends ColumnType
{
    function to_sql()
    {
        return 'VARCHAR(255) PRIMARY KEY NOT NULL';
    }
}
