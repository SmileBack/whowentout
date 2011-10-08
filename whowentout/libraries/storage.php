<?php

class Storage extends Component
{

    function __construct()
    {
        parent::__construct();
    }

    function save($place, $filename, $file)
    {
        $this->mount($place);
        return $this->driver()->save($filename, $file);
    }

    function getText($place, $filename)
    {
        return $this->driver()->getText($filename);
    }

    function saveText($place, $filename, $text)
    {
        $this->mount($place);
        return $this->driver()->saveText($filename, $text);
    }

    function exists($place, $filename)
    {
        $this->mount($place);
        return $this->driver()->exists($filename);
    }

    function delete($place, $filename)
    {
        $this->mount($place);
        return $this->driver()->delete($filename);
    }

    function url($place, $filename)
    {
        $this->mount($place);
        return $this->driver()->url($filename);
    }

}
