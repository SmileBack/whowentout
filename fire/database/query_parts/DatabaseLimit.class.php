<?php

class DatabaseLimit extends QueryPart
{

    private $limit;

    function __construct($limit)
    {
        $this->limit = $limit;
    }

    function to_sql()
    {
        return "LIMIT $this->limit";
    }

}
