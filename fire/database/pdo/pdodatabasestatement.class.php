<?php

class PdoDatabaseStatement extends PDOStatement
{

    /**
     * @var $dbh PdoDatabaseHandle
     */
    public $dbh;

    private static $queries = array();

    protected function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    function execute($input_parameters = array())
    {
        benchmark::start($this->queryString, 'database');
        $result = call_user_func_array(array($this, 'parent::execute'), func_get_args());
        benchmark::end($this->queryString, 'database');

        return $result;
    }

}
