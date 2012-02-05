<?php

class TestAction extends Action
{

    function execute()
    {
        /* @var $invite_engine InviteEngine */
        $invite_engine = build('invite_engine');

        $event = db()->table('events')->row(32);
        $user = db()->table('users')->row(8212);

        print db()->table('events')->order_by('place.name')->to_sql();
    }

}
