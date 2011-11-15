<?php

class Database
{

    /* @var PDO */
    public $dbh = NULL;

    private $config = array();
    private $tables = array();
    
    function __construct($config)
    {
        $this->connect($config);
        $this->load_tables();
    }

    function connect(array $config)
    {
        $this->config = $config;
        $this->dbh = new PDO("mysql:host={$config['host']};dbname={$config['database']}",
            $config['username'], $config['password']);

        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->load_tables();
    }

    function name()
    {
        return $this->config['name'];
    }

    function begin_transaction()
    {
        $this->dbh->beginTransaction();
    }

    function commit_transaction()
    {
        $this->dbh->commit();
    }

    function rollback_transaction()
    {
        $this->dbh->rollBack();
    }

    function last_insert_id()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * @param  $table_name
     * @return DatabaseTable
     */
    function table($table_name)
    {
        if (!isset($this->tables[$table_name])) {
            $this->tables[$table_name] = new DatabaseTable($this, $table_name);
        }

        return $this->tables[$table_name];
    }

    function create_table($table_name, $columns)
    {
        $sql = $this->create_table_sql($table_name, $columns);
        $query = $this->query_statement($sql);
        $query->execute();
    }

    function rename_table($table_name, $new_table_name)
    {
        $query = $this->query_statement("RENAME $table_name TO $new_table_name");
        $query->execute();
    }

    function destroy_table($table_name)
    {
        $query = $this->query_statement("DROP TABLE $table_name");
        $query->execute();
    }

    function drop_table($table_name)
    {
        throw new Exception("Please use the destroy_table method.");
    }

    function create_table_sql($table_name, $columns)
    {
        $column_schemas = array();
        foreach ($columns as $column_name => $column_config) {
            $column_schemas[] = $this->get_column_sql($column_name, $column_config);
        }

        return "CREATE TABLE $table_name\n"
               . "(\n"
               . implode(",\n", $column_schemas) . "\n"
               . ") ENGINE=INNODB";
    }

    private function get_column_sql($column_name, $column_config)
    {
        $column_type = f()->class_loader()->init_subclass('columntype', $column_config['type'], $column_config);
        return $column_name . ' ' . $column_type->to_sql();
    }

    private function load_tables()
    {
        $statement = $this->query_statement('SHOW TABLES');
        $statement->execute();
        $table_names = $statement->fetchAll(PDO::FETCH_COLUMN);

        foreach ($table_names as $name) {
            // lazy loading of schema.
            // when we ask for the schema of a table for the first time, the schema will be loaded into $this->tables[table name]
            $this->tables[$name] = NULL;
        }
    }

    /**
     *
     * @param string $sql An SQL query
     * @param array $params If placeholders are used in your sql, place your values in $params.
     * @return PDOStatement
     */
    function query_statement($sql, $params = array())
    {
        $statement = $this->dbh->prepare($sql);
        foreach ($params as $name => $value) {
            // PDO wants all parameter placeholders to start with a ':'
            $placeholder = $name{0} == ':' ? $name : ":$name";
            $statement->bindParam($placeholder, $params[$name]);
        }
        return $statement;
    }

}
