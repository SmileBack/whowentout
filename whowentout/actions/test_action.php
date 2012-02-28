<?php

class TestAction extends Action
{

    function execute()
    {
        print app()->places_dropdown('places', 'bar base');
    }

}
