<?php

class flow
{

    public static function get()
    {
        return isset($_SESSION['__flow']);
    }

    public static function set($value)
    {
        $_SESSION['__flow'] = $value;
    }

    public static function end()
    {
        unset($_SESSION['__flow']);
    }

}

