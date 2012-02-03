<?php

class MutualFriendsCalculator
{

    /* @var $database Database */
    private $database;

    function __construct(Database $database)
    {
        $this->database = $database;
    }

    function compute($user_a_id, $user_b_id)
    {
        $friends = array();

        $query = $this->mutual_friends_query($user_a_id, $user_b_id);
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_OBJ);

        foreach ($rows as $row) {
            $friends[] = $this->database->table('users')->row($row->id);
        }

        return $friends;
    }

    function mutual_friends_query($user_a_id, $user_b_id)
    {
        $query = "SELECT users.id AS id, users.facebook_id AS facebook_id, first_name, last_name FROM user_friends AS a
                    INNER JOIN user_friends AS b
                        ON a.user_id = :a_id AND b.user_id = :b_id AND a.friend_id = b.friend_id
                    INNER JOIN users
                        ON a.friend_id = users.id
                    ORDER BY users.first_name, users.last_name ASC";

        return $this->database->query_statement($query, array(
            'a_id' => $user_a_id,
            'b_id' => $user_b_id,
        ));
    }

}
