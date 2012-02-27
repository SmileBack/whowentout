<?php

class TestAction extends Action
{

    function execute()
    {
        print session_cache_expire();
    }

}
