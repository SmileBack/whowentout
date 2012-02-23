<?php

class ViewLeaderboardAction extends Action
{

    function execute($y, $m, $d)
    {
        $date = new XDateTime("$y/$m/$d", build('timezone'));
        print r::invite_leaderboard(array(
            'date' => $date,
        ));
    }

}
