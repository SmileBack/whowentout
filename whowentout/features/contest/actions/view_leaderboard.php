<?php

class ViewLeaderboardAction extends Action
{

    function execute($y, $m, $d)
    {
        $date = DateTime::createFromFormat('Y/m/d', "$y/$m/$d");
        print r::invite_leaderboard(array(
            'date' => $date,
        ));
    }

}
