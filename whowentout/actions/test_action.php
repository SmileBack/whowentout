<?php

class TestAction extends Action
{

    /**
     * @var Database
     */
    private $database;

    function execute()
    {
        /* @var $storage FileRepository */
        $storage = build('js_storage');

        $storage->create('jquery.0000000001.js', 'js/jquery.js');

        print $this->url();
    }

    function url()
    {
        $protocol = 'http';
        return $protocol . "://" . $_SERVER['HTTP_HOST'] . '/';
    }


}
