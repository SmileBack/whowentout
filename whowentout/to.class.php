<?php

class to
{

    static function event($value)
    {
        return static::row('events', $value);
    }

    static function user($value)
    {
        return static::row('users', $value);
    }

    static function place($value)
    {
        return static::row('places', $value);
    }

    private static function row($table, $value)
    {
        if (is_int($value) || is_string($value))
            return db()->table($table)->row($value);
        elseif ($value instanceof DatabaseRow)
            return $value;
        else
            return null;
    }

}

