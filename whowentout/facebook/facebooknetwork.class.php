<?php

class FacebookNetwork
{

    public $id;
    public $type;
    public $name;

    private $data;
    
    function __construct($data)
    {
        $this->data = $data;

        $this->id = $this->data['nid'];
        $this->type = $this->data['type'];
        $this->name = $this->data['name'];
    }

}
