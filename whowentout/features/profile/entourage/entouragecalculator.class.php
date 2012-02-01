<?php

class EntourageCalculator
{

    /* @var $database Database */
    private $database;

    function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param $user_id
     * @return DatabaseRow[]
     */
    function compute($user_id)
    {
        $users = array();

        $query = $this->entourage_query($user_id);
        $query->execute();

        $rows = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($rows as $row)
            $users[] = $this->database->table('users')->row($row->id);

        return $users;
    }

    function entourage_query($user_id)
    {
        $query = "SELECT users.id AS id FROM checkins AS checkins_a
        	INNER JOIN checkins as checkins_b
        		ON checkins_a.user_id = :id AND checkins_b.user_id != :id AND checkins_a.event_id = checkins_b.event_id
        	INNER JOIN users
        		ON checkins_b.user_id = users.id
        	INNER JOIN user_friends
        		ON user_friends.user_id = :id AND user_friends.friend_id = checkins_b.user_id";
        return $this->database->query_statement($query, array('id' => $user_id));
    }

}
