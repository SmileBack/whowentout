<?php

class TestAction extends Action
{

    /**
     * @var Database
     */
    private $database;

    function execute()
    {
        $gallery = r::event_gallery(array(
            'user' => auth()->current_user(),
            'date' => app()->clock()->today(),
        ));

        print r::page(array(
            'content' => $gallery,
        ));
    }

}
