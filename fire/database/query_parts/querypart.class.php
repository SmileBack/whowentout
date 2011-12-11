<?php

abstract class QueryPart
{
    
    /**
     * @abstract
     * @return DatabaseTableJoin[]
     */
    function joins()
    {
        return array();
    }

    /**
     * @abstract
     * @return strings
     */
    abstract function to_sql();
}