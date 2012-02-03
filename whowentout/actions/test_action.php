<?php

class TestAction extends Action
{

    function execute()
    {

        $invite = db()->table('invites')->row(18);
        $request = db()->table('entourage_requests')->row(54);
    }

}
