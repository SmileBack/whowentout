<?php

class TestAction extends Action
{

    function execute()
    {
        app()->notify_admins('test notification', 'test notification');
    }

}
