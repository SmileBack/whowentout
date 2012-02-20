<?php

class TestAction extends Action
{

    function execute()
    {
        print r::invite_leaderboard(array(
            'date' => app()->clock()->today()->getDay(+2),
        ));
    }

}
