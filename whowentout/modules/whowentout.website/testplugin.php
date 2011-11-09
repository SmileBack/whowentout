<?php

class TestPlugin extends Plugin
{
    function on_boom($e)
    {
        krumo::dump(get_class($e));
        krumo::dump($e);
    }
}

class BoomEvent extends Event
{
    public $stuff = 'woo';
    public $name;
}
