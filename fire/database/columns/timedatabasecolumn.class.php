<?php

class TimeDatabaseColumn extends DatabaseColumn
{

    function from_database_value($value)
    {
        return new DateTime($value, new DateTimeZone('UTC'));
    }

    function to_database_value($value)
    {
        return $value->format('Y-m-d H:i:s');
    }

}
