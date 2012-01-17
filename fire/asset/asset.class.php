<?php

class Asset
{

    /**
     * @var Index
     */
    private $index;

    /**
     * @var FileRepository
     */
    private $storage;

    private $js = array();

    function __construct(Index $index, FileRepository $storage)
    {
        $this->index = $index;
        $this->storage = $storage;
    }

    function load_js($js_name)
    {
        $this->js[$js_name] = true;
    }



}
