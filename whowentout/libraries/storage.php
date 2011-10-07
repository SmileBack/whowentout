<?php

class Storage extends Component
{

    function __construct()
    {
        parent::__construct();
    }

    public function save($place, $filename, $file)
    {
        $this->mount($place);
        return $this->driver()->save($filename, $file);
    }

    public function saveText($place, $filename, $text)
    {
        $this->mount($place);
        return $this->driver()->saveText($filename, $text);
    }

    public function exists($place, $filename)
    {
        $this->mount($place);
        return $this->driver()->exists($filename);
    }

    public function delete($place, $filename)
    {
        $this->mount($place);
        return $this->driver()->delete($filename);
    }

    public function url($place, $filename)
    {
        $this->mount($place);
        return $this->driver()->url($filename);
    }

}
