<?php

class TestAction extends Action
{

    function execute()
    {
        print r::entourage_invite();
    }

    function execute2sadf()
    {
        $current_user = auth()->current_user();

        $brian = db()->table('users')->where('last_name', 'Bulcke')->first();
        $dan = db()->table('users')->where('last_name', 'Berenholtz')->first();

        /* @var $entourage_engine EntourageEngine */
        $entourage_engine = build('entourage_engine');

        $entourage_engine->send_request($brian, $dan);
    }

}
