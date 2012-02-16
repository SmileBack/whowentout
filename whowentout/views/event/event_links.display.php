<?php

class Event_Links_Display extends Display
{

    function process()
    {
        $this->link_data = $this->get_link_data($this->date);
    }

    function get_link_data(DateTime $date)
    {
        $query = db()->query_statement("SELECT events.id, COUNT(checkins.id) AS count  FROM checkins
                                    INNER JOIN events ON checkins.event_id = events.id
                                    WHERE events.date = :date
                                    GROUP BY events.id
                                    ORDER BY count DESC", array('date' => $date->format('Y-m-d')));
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }

}
