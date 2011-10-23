<?php

class WooPlugin extends Plugin
{

    function __construct($name)
    {
        $this->name = $name;
    }

    function on_say_hi($e)
    {
        print "<h1>hi, {$e->name}</h1>";
    }

}
