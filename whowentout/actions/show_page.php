<?php

class ShowPageAction extends Action
{

    function execute($view_name)
    {
        print r::page(array(
            'content' => Display::r($view_name),
        ));
    }

}
