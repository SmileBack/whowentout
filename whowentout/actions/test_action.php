<?php

class TestAction extends Action
{

    function execute()
    {
        $dt = new DateTime('2011-12-09');
        $query = db()->query_statement("SELECT events.id, COUNT(checkins.id) AS count  FROM checkins
                                    INNER JOIN events ON checkins.event_id = events.id
                                    WHERE events.date = :date
                                    GROUP BY events.id", array('date' => $dt->format('Y-m-d')));
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($results as $row) {
            krumo::dump($row);
        }
    }

    private function grammarize($text, $data = array())
    {

    }

}
