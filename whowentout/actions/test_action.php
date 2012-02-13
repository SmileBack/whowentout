<?php

class TestAction extends Action
{

    function execute()
    {
        print r::page(array(
            'content' => r::terms(),
        ));
    }

}
